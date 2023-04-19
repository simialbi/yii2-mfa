<?php

use yii\widgets\MaskedInput;

/** @var $this \yii\web\View */
/** @var $model \simialbi\yii2\mfa\models\EnableTotpForm */
/** @var $totp \RobThree\Auth\TwoFactorAuth */
/** @var $identity \simialbi\yii2\mfa\models\TotpIdentityInterface */
/** @var $suffix string */

/** @var \yii\widgets\ActiveForm $activeForm */
$activeForm = "\\yii\\bootstrap$suffix\\ActiveForm";
/** @var \yii\helpers\Html $html */
$html = "\\yii\\bootstrap$suffix\\Html";
?>

<div class="sa-totp-form">
    <?php $form = $activeForm::begin(); ?>

    <?= $form->field($model, 'secret', ['options' => ['class' => ['m-0', 'p-0']]])->hiddenInput()->label(false); ?>

    <div class="row form-row g-3">
        <div class="col-xs-8 col-8 col-xs-offset-2 offset-2">
            <img src="<?= $totp->getQRCodeImageAsDataUri($identity->getUsername(), $model->secret, 300); ?>"
                 alt="QR Code token for <?= $identity->getUsername(); ?>">
        </div>
    </div>
    <div class="row form-row g-3">
        <div class="col-xs-8 col-8 col-xs-offset-2 offset-2">
            <p><?= Yii::t('simialbi/mfa', 'Please enter the 6 digit long token from your preferred authenticator app.'); ?></p>
            <?= $form->field($model, 'token')->widget(MaskedInput::class, [
                'mask' => '999999'
            ]); ?>
        </div>
    </div>
    <div class="row form-row g-3">
        <div class="col-xs-4 col-4 col-xs-offset-4 offset-4">
            <?= $html::submitButton(Yii::t('simialbi/mfa', 'Submit'), [
                'class' => ['btn', 'btn-success']
            ]); ?>
        </div>
    </div>

    <?php $activeForm::end(); ?>
</div>
