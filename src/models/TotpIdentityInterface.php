<?php

namespace simialbi\yii2\mfa\models;

use yii\web\IdentityInterface;

interface TotpIdentityInterface extends IdentityInterface
{
    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public static function findIdentity($id): static;

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public static function findIdentityByAccessToken($token, $type = null): static;

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
