<?php

namespace simialbi\yii2\mfa\models;

use RobThree\Auth\Algorithm;
use RobThree\Auth\TwoFactorAuth;
use RobThree\Auth\TwoFactorAuthException;
use simialbi\yii2\mfa\validators\TotpValidator;
use Yii;
use yii\base\Model;
use yii\validators\InlineValidator;

class TotpForm extends Model
{
    /**
     * @var string The 6digit totp token
     */
    public $token;

    /**
     * @var TotpIdentityInterface Identity to validate token against
     */
    private TotpIdentityInterface $_identity;

    /**
     * Constructor of TotpForm
     * @param TotpIdentityInterface $identity The identity to verify the token for.
     * @param array $config Configuration array for the public properties.
     */
    public function __construct(TotpIdentityInterface $identity, array $config = [])
    {
        $this->_identity = $identity;

        parent::__construct($config);
    }

    /**
     * {@inheritDoc}
     */
    public function rules(): array
    {
        return [
            ['token', 'string', 'length' => 6],
            ['token', TotpValidator::class, 'identity' => $this->_identity],
            ['token', 'required']
        ];
    }

    /**
     * Validates the totp token against the input.
     * @param string $attribute the attribute currently being validated
     * @param array|null $params the value of the "params" given in the rule
     * @param InlineValidator $validator related InlineValidator instance.
     *
     * @return void
     *
     * @throws TwoFactorAuthException
     */
    public function validateToken(string $attribute, ?array $params, InlineValidator $validator): void
    {
        $totp = new TwoFactorAuth(null, 6, 30, Algorithm::Sha1);
        if (!$totp->verifyCode($this->_identity->getTotpToken(), $this->$attribute)) {
            $validator->addError($this, $attribute, Yii::t('simialbi/mfa/validator', 'Invalid token.'));
        }
    }

    /**
     * {@inheritDoc}
     */
    public function attributeLabels(): array
    {
        return [
            'token' => Yii::t('simialbi/mfa/model/totp-form', 'Token')
        ];
    }
}
