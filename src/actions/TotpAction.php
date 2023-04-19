<?php

namespace simialbi\yii2\mfa\actions;

use simialbi\yii2\mfa\models\TotpForm;
use Yii;
use yii\base\Action;
use yii\web\ForbiddenHttpException;

class TotpAction extends Action
{
    /**
     * @var string|array The route or url to redirect to after login
     */
    public string|array $redirectRoute;

    /**
     * @var string|array The route to the [[\simialbi\yii2\mfa\actions\EnableTotpAction]].
     */
    public string|array $enableTotpRoute;

    /**
     * @var int Number of seconds that the user can remain in logged-in status, defaults to `0`
     */
    public int $loginDuration = 0;

    /**
     * @var string The view to render the form. The following parameters will be passed:
     *  - `$form` [[\simialbi\yii2\mfa\models\TotpForm]]: The model
     *  - `$identity` [[\simialbi\yii\mfa\models\TotpIdentityInterface]]: The identity logged in in first step
     */
    public string $view = '@simialbi/yii2/mfa/views/totp-form';

    /**
     * @var string Bootstrap version suffix (e.g '4')
     */
    public string $bsSuffix = '';

    /**
     * {@inheritDoc}
     */
    public function init(): void
    {
        parent::init();

        if (!isset($this->redirectRoute)) {
            $this->redirectRoute = Yii::$app->homeUrl;
        }
        if (isset(Yii::$app->params['bsVersion'])) {
            $this->bsSuffix = substr(Yii::$app->params['bsVersion'], 0, 1);
        }
        if (!class_exists("\\yii\\bootstrap{$this->bsSuffix}\\Html")) {
            if (class_exists('\yii\bootstrap4\Html')) {
                $this->bsSuffix = '4';
            } elseif (class_exists('\yii\bootstrap5\Html')) {
                $this->bsSuffix = '5';
            }
        }
    }

    /**
     * Run the totp action
     *
     * @return string|\yii\web\Response
     *
     * @throws ForbiddenHttpException
     */
    public function run(): string|\yii\web\Response
    {
        /** @var \simialbi\yii2\mfa\models\TotpIdentityInterface $identity */
        $identity = Yii::$app->session->get('mfa-half-user');
        $model = new TotpForm($identity);

        if (empty($identity->getTotpToken())) {
            if (isset($this->enableTotpRoute)) {
                return $this->controller->redirect($this->enableTotpRoute);
            }

            throw new ForbiddenHttpException(Yii::t(
                'simialbi/mfa/notifications',
                'You have to activate two factor authentication to access this resource.'
            ));
        }
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            Yii::$app->user->detachBehavior('validateMfa');
            Yii::$app->user->login($identity, $this->loginDuration);

            return $this->controller->redirect($this->redirectRoute);
        }

        $model->token = '';

        return $this->controller->render($this->view, [
            'model' => $model,
            'identity' => $identity,
            'suffix' => $this->bsSuffix
        ]);
    }
}
