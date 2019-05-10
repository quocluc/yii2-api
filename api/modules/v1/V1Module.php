<?php
/**
 * Created by PhpStorm.
 * User: truongn
 * Date: 11/9/17
 * Time: 3:53 PM
 */
namespace  api\modules\v1;

class V1Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'api\modules\v1\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        \Yii::$app->user->enableSession = false;

    }
}