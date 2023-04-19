<?php

namespace simialbi\yii2\mfa\models;

use simialbi\yii2\mfa\validators\TotpValidator;
use yii\base\Model;

class EnableTotpForm extends Model
{
    /**
     * @var string The totp secret
     */
    public $secret;

    /**
     * @var string The 6digit totp token
     */
    public $token;

    /**
     * {@inheritDoc}
     */
    public function rules(): array
    {
        return [
            ['secret', 'string'],
            ['token', 'string', 'length' => 6],
            ['token', TotpValidator::class, 'secretAttribute' => 'secret'],

            [['secret', 'token'], 'required']
        ];
    }
}
