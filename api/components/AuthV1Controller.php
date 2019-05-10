<?php

namespace api\components;

use api\components\LoginRequiredTrait;

use common\models\Constant;
use common\models\User;
use api\components\RestController;
use yii\web\ForbiddenHttpException;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBearerAuth;


class AuthV1Controller extends RestController
{


    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $auth = $behaviors['authenticator'];
        unset($behaviors['authenticator']);
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className(),
        ];
        $behaviors['authenticator'] = ['class' => HttpBearerAuth::className()];
        $behaviors['authenticator']['except'] = ['options'];
        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'rules' => [
                [
                    'allow' => true,
                    'roles' => ['owner_product', 'owner_vehicle', 'driver'],
                ],
            ],
        ];
        return $behaviors;
    }

    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        if (empty(\Yii::$app->user->identity->confirmed_email)) {
            throw new ForbiddenHttpException('Please verify account');
        }

        if (\Yii::$app->user->identity->status != User::STATUS_ACTIVE) {
            throw new ForbiddenHttpException('User đã bị khóa bởi hệ thống. Liên hệ admin để biết thêm thông tin.');
        }


        if (\Yii::$app->user->can(User::OWNER_DRIVER)
            && \Yii::$app->user->identity->is_admin_active == Constant::DRIVER_DEACTIVE_BY_ADMIN
            && !\Yii::$app->user->can(User::OWNER_DRIVER)
        ) {
            throw new ForbiddenHttpException('Tài xế chưa được admin active. Vui lòng liên hệ admin loglag');
        }

        return true;
    }
}