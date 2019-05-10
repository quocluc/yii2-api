<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;

class RuleController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;
        try {

            echo $auth->revoke($auth->getRole('staff'), 2);
        } catch (\Exception $exception) {
            echo $exception;
        }
    }

    public function actionRun()
    {
        $auth = Yii::$app->authManager;
        $auth->assign($auth->getRole('staff'), 2);
    }
}