<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'api\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'v1' => [
            'class' => 'api\modules\v1\V1Module',
        ],
    ],
    'components' => [
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'request' => [
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
            'enableCsrfValidation' => false,
            'cookieValidationKey' => 'AED5BBDE1347C8043E6B5E6CE11792874261A68E785DD03FB3703B3483260BAF',

        ],
        'response' => [
            'class' => 'yii\web\Response',
            'on beforeSend' => function ($event) {
                $response = $event->sender;
                if ($response->data !== null) {
                    $response->format = yii\web\Response::FORMAT_JSON;

                    if ($response->statusCode == 200) {

                        $response->data =
                            [
                                'success' => $response->isSuccessful,
                                'data' => !empty($response->data['message']) ? $response->data['message'] : $response->data,
                                'status_code' => $response->statusCode
                            ];
                        $response->setStatusCode($response->statusCode);
                    } else {
                        $response->data =
                            [
                                'success' => $response->isSuccessful,
                                'data' => !empty($response->data['message']) ? $response->data['message'] : $response->data,
                                'status_code' => $response->statusCode
                            ];
                        $response->setStatusCode($response->statusCode);
                    }
                }
            },
        ],
        'user' => [
            'class' => 'api\components\ApiWebUser',
            'identityClass' => 'api\models\User',
            'enableSession' => false,
            'loginUrl' => null
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'site/error' => 'site/error',
                'v1/login' => 'v1/login/login',
                'v1/logout' => 'v1/login/logout',
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['v1/user'],
                ],
            ],
        ],

    ],
    'params' => $params,
];
