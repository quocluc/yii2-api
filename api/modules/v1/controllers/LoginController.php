<?php


namespace api\modules\v1\controllers;

use Yii;
use api\components\RestController;
use api\models\LoginForm;
use api\models\User;
use yii\filters\AccessControl;

class LoginController extends RestController
{
    public $modelClass = 'api\models\User';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                ],
            ],
            'corsFilter' => [
                'class' => \yii\filters\Cors::className(),
            ],
        ];
    }

    public function actions()
    {
        $actions = parent::actions();

        // disable the "delete" and "create" actions
        unset($actions['index'], $actions['create']);


        return $actions;
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return "logged user";
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->getRequest()->getBodyParams(), '')) {
            if ($model->validate()) {

                if ($model->login()) {
                    $profile = User::findOne(Yii::$app->user->id);

                    $result = [
                        'access_token' => Yii::$app->user->identity->access_token,
                        'user' => !empty($profile) ? $profile : '',
                        'role' => $profile->getRoleText(),
                    ];

                    $profile->save(false);

                    return $this->apiResponseOk($result);

                } else {
                    return $this->apiResponseFailed($model->errors, 500);
                }

            } else
                return $this->apiResponseFailed($model->errors, 500);

        } else {
            return $this->apiResponseFailed($model->errors, 500);
        }
    }

    public function actionLogout()
    {
        /*
         * Không nhận được action vì không nhận được token tại đây
         * Chuyển action sang một controller mới
         * Có thể update thông tin cá nhân
         *  :(
         */
        //TODO:: Chuyển logout sang một controller mới :(
        if (Yii::$app->user->isGuest) {
            return $this->apiResponseFailed("You are not a allow for this!", 403);
        } else {
            $user = Yii::$app->user->identity;
            $user->access_token = null;
            $user->save(false);
            return $this->apiResponseOk('Logout success');
        }
    }

    public function actionSignup()
    {
        if (!Yii::$app->user->isGuest) {
            return "logged user";
        }

    }
}