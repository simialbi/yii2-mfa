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
     * Get the identity's username
     *
     * @return string
     */
    public function getUsername(): string;

    /**
     * Get the totp token
     *
     * @return string|null
     */
    public function getTotpToken(): ?string;

    /**
     * Set the totp token
     *
     * @param string $token
     *
     * @return void
     */
    public function setTotpToken(string $token): void;

    /**
     * Saves the current identity (or at least the totp token).
     *
     * @param bool $runValidation whether to perform validation (calling [[\yii\base\Model::validate()|validate()]])
     * before saving the record. Defaults to `true`. If the validation fails, the record
     * will not be saved to the database and this method will return `false`.
     * @param array|null $attributeNames list of attribute names that need to be saved. Defaults to `null`,
     * meaning all attributes that are loaded from DB will be saved.
     * @return bool whether the saving succeeded (i.e. no validation errors occurred).
     */
    public function save(bool $runValidation = true, ?array $attributeNames = null): bool;
}
