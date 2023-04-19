<?php

namespace simialbi\yii2\mfa\validators;

use RobThree\Auth\TwoFactorAuth;
use simialbi\yii2\mfa\models\TotpIdentityInterface;
use Yii;
use yii\base\NotSupportedException;
use yii\validators\Validator;

/**
 * Validates a totp token against the input.
 */
class TotpValidator extends Validator
{
    /**
     * @var string The attribute which holds the totp secret to validate the input against. If not set, it try to
     * validate secret from identity passed.
     */
    public string $secretAttribute;

    /**
     * @var TotpIdentityInterface identity to validate the input against.
     */
    public TotpIdentityInterface $identity;

    /**
     * {@inheritDoc}
     */
    public function init(): void
    {
        parent::init();

        if (!isset($this->identity)) {
            $this->identity = Yii::$app->user->identity;
        }

        if ($this->message === null) {
            $this->message = Yii::t('simialbi/mfa/validator', 'Token invalid.');
        }
    }

    /**
     * {@inheritDoc}
     * @throws NotSupportedException
     */
    public function validateAttribute($model, $attribute): void
    {
        $result = isset($this->secretAttribute) && $model->hasProperty($this->secretAttribute)
            ? $this->validateValue($model->$attribute, $model->{$this->secretAttribute})
            : $this->validateValue($model->$attribute);
        if (!empty($result)) {
            $this->addError($model, $attribute, $result[0], $result[1]);
        }
    }

    /**
     * {@inheritDoc}
     * @param string|null $secret The secret to use to validate the value
     */
    protected function validateValue($value, ?string $secret = null): ?array
    {
        $totp = new TwoFactorAuth();

        if ($secret === null) {
            $secret = $this->identity->getTotpToken();
        }
        if (!$totp->verifyCode($secret, $value)) {
            return [$this->message, []];
        }

        return null;
    }
}
