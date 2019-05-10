<?php

namespace api\models;

use Yii;
use yii\helpers\ArrayHelper;

class User extends \common\models\User
{

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    public function getRoleText(): string
    {
        $role = Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId());
        if ($role != null)
            return key($role);
        else
            return null;
    }

}