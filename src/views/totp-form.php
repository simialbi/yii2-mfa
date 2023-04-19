<?php

use yii\widgets\MaskedInput;

/** @var $this \yii\web\View */
/** @var $model \simialbi\yii2\mfa\models\TotpForm */
/** @var $identity \simialbi\yii2\mfa\models\TotpIdentityInterface */
/** @var $suffix string */

/** @var \yii\widgets\ActiveForm $activeForm */
$activeForm = "\\yii\\bootstrap$suffix\\ActiveForm";
/** @var \yii\helpers\Html $html */
$html = "\\yii\\bootstrap$suffix\\Html";
?>

<div class="sa-totp-form">
    <?php $form = $activeForm::begin(); ?>

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
