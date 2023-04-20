<?php

namespace simialbi\yii2\mfa\actions;

use RobThree\Auth\Algorithm;
use RobThree\Auth\Providers\Qr\EndroidQrCodeProvider;
use RobThree\Auth\TwoFactorAuth;
use simialbi\yii2\mfa\models\EnableTotpForm;
use Yii;
use yii\base\Action;
use yii\web\ForbiddenHttpException;

class EnableTotpAction extends Action
{
    /**
     * @var string|array The route or url to redirect to after login
     */
    public string|array $redirectRoute;

    /**
     * @var string The view to render the form. The following parameters will be passed:
     *  - `$form` [[\simialbi\yii2\mfa\models\EnableTotpForm]]: The model
     */
    public string $view = '@simialbi/yii2/mfa/views/enable-totp-form';

    /**
     * @var string|null The issuer of the two factor authentication. This string will be shown in the app.
     */
    public ?string $issuer = null;

    /**
     * @var string Bootstrap version suffix (e.g '4')
     */
    public string $bsSuffix = '';

    /**
     * {@inheritDoc}
     * @throws ForbiddenHttpException|\yii\base\ExitException
     */
    public function init(): void
    {
        parent::init();

        if (!isset($this->redirectRoute)) {
            $this->redirectRoute = Yii::$app->homeUrl;
        }

        if (!Yii::$app->user->identity) {
            throw new ForbiddenHttpException('The user has to be logged in to perform this action.');
        }

        if (Yii::$app->user->identity->getTotpToken()) {
            Yii::$app->session->addFlash('danger', Yii::t('simialbi/mfa/notification', 'Two factor authentication is already enabled.'));

            $response = $this->controller->goBack();
            $response->send();
            Yii::$app->end();
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
     * Run the enable totp action
     *
     * @return \yii\web\Response|string
     *
     * @throws \RobThree\Auth\TwoFactorAuthException
     */
    public function run(): \yii\web\Response|string
    {
        $totp = new TwoFactorAuth($this->issuer, 6, 30, Algorithm::Sha1, new EndroidQrCodeProvider());
        $model = new EnableTotpForm([
            'secret' => $totp->createSecret()
        ]);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            Yii::$app->user->identity->setTotpToken($model->secret);
            Yii::$app->user->identity->save();
            return $this->controller->redirect($this->redirectRoute);
        }

        return $this->controller->render($this->view, [
            'model' => $model,
            'totp' => $totp,
            'identity' => Yii::$app->user->identity,
            'suffix' => $this->bsSuffix
        ]);
    }
}
