<?php

namespace simialbi\yii2\mfa\behaviors;

use Yii;
use yii\base\Behavior;
use yii\base\InvalidConfigException;
use yii\web\User;
use yii\web\UserEvent;

class TotpBehavior extends Behavior
{
    /**
     * @var string|array The route to the controller containing the [[\simialbi\yii2\mfa\actions\TotpAction]].
     */
    public string|array $totpRoute;

    /**
     * {@inheritDoc}
     */
    public function events(): array
    {
        return [
            User::EVENT_BEFORE_LOGIN => 'beforeLogin'
        ];
    }

    /**
     * @param UserEvent $event
     * @return void
     * @throws InvalidConfigException|\yii\base\ExitException
     */
    public function beforeLogin(UserEvent $event): void
    {
        if ($event->cookieBased) {
            return;
        }
        if (!isset($this->totpRoute)) {
            throw new InvalidConfigException('The `totpRoute` has to be set!');
        }
        $event->isValid = false;
        Yii::$app->session->set('mfa-half-user', $event->identity);
        $response = Yii::$app->controller->redirect($this->totpRoute);
        $response->send();
        Yii::$app->end();
    }
}
