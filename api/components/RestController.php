<?php

namespace api\components;

use \yii\helpers\Json;

class RestController extends \yii\rest\ActiveController
{
    public $modelClass = 'common\models\User';

    public function apiResponseFailed($model = null, $code = 500, $message = null)
    {
        \Yii::$app->response->statusCode = $code;
        return [
            'message' => $this->getErrorMsg((empty($model) ? $message : $model))
        ];
    }


    public function apiResponseOk($model)
    {
        \Yii::$app->response->statusCode = 200;
        return $model;
    }

    private function getErrorMsg($model)
    {
        if (is_array($model)) {
            return !empty(array_values($model)[0][0]) ? array_values($model)[0][0] : array_values($model);
        } else return $model;
    }

    public static function loadProperty(&$result)
    {

        if (isset($_POST['json_object_param'])) {
            $jsonString = $_POST['json_object_param'];
            $data = Json::decode($jsonString);
            foreach ($data as $key => $value) {
                $result->{$key} = $value;
            }
        }

    }
}