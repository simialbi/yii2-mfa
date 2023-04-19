<?php

namespace simialbi\yii2\mfa\models;

use yii\web\IdentityInterface;

interface TotpIdentityInterface extends IdentityInterface
{
    /**
     * {@inheritDoc}
     *
     * @return mixed
     */
    public static function findIdentity($id): mixed;

    /**
     * {@inheritDoc}
     *
     * @return mixed
     */
    public static function findIdentityByAccessToken($token, $type = null): mixed;

    /**
     * Get the totp token
     *
     * @return string
     */
    public function getTotpToken(): string;

    /**
     * Set the totp token
     *
     * @param string $token
     *
     * @return void
     */
    public function setTotpToken(string $token): void;
}
