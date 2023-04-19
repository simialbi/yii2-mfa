<?php

/**
 * Yii bootstrap file.
 * Used for enhanced IDE code autocompletion.
 */
class Yii extends \yii\BaseYii
{
    /**
     * @var BaseApplication|WebApplication|ConsoleApplication the application instance
     */
    public static $app;
}

/**
 * Class BaseApplication
 *
 * @property \yii\web\User|User $user
 */
abstract class BaseApplication extends \yii\base\Application
{
}

/**
 * Class WebApplication
 */
class WebApplication extends \yii\web\Application
{
}

/**
 * Class ConsoleApplication
 */
class ConsoleApplication extends \yii\console\Application
{
}

/**
 * @property \yii\web\IdentityInterface|\simialbi\yii2\mfa\models\TotpIdentityInterface $identity
 */
class User extends \yii\web\User
{

}
