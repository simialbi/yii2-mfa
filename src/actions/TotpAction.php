<?php

namespace simialbi\yii2\mfa\actions;

use simialbi\yii2\mfa\models\TotpForm;
use Yii;
use yii\base\Action;

class TotpAction extends Action
{
    /**
     * @var string|array|null The route or url to redirect to after login
     */
    public string|array|null $redirectRoute = null;

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

        if (is_null($this->redirectRoute)) {
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
     */
    public function run(): string|\yii\web\Response
    {
        /** @var \simialbi\yii2\mfa\models\TotpIdentityInterface $identity */
        $identity = Yii::$app->session->get('mfa-half-user');
        $form = new TotpForm($identity);

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            Yii::$app->user->detachBehavior('validateMfa');
            Yii::$app->user->login($identity);

            return $this->controller->redirect($this->redirectRoute);
        }

        return $this->controller->render($this->view, [
            'form' => $form,
            'identity' => $identity,
            'suffix' => $this->bsSuffix
        ]);
    }
}
