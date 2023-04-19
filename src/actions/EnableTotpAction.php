<?php

namespace simialbi\yii2\mfa\actions;

use yii\base\Action;
use yii\web\Response;

class EnableTotpAction extends Action
{
    public function run(): Response|string
    {


        return $this->controller->render('');
    }
}
