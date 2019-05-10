<?php

namespace api\components;

use yii\filters\auth\HttpBearerAuth;

trait LoginRequiredTrait
{
    public function behaviors()
    {
        parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
        ];
        return $behaviors;
    }
}