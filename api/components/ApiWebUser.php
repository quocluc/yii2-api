<?php

namespace api\components;

use yii\web\User;

class ApiWebUser extends User
{
    public function loginByAccessToken($token, $type = null)
    {
        return parent::loginByAccessToken($token, $type);

    }
}