<?php

namespace api\components;

use Yii;

class ApiHelper
{

    public static function initFolderUpload($path)
    {
        $pathInit = \Yii::getAlias('@api/web/') . $path;

        if (!is_dir($pathInit)) {
            mkdir($pathInit,  0777, $recursive = true);
            chmod($pathInit, 0777);
        } else {
            if (!is_writable($pathInit)) {
                system("/bin/chmod -R 0777 $pathInit");
            }
        }
    }


}