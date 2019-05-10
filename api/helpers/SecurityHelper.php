<?php
/**
 * Created by PhpStorm.
 * User: truongnguyen
 * Date: 11/13/17
 * Time: 1:56 PM
 */


namespace api\helpers;

use yii\web\BadRequestHttpException;

class SecurityHelper
{
    const KEY = 'byDY*%Ue5qVN6kX2CYZ-)*#Q^_03048yG2pxNP';

    public static function createSignature($value)
    {
        return hash('sha256', self::KEY.$value);
    }

    public static function checkSignature($signature, $value)
    {
        return self::createSignature($value) == $signature;
    }

    public static function generateAccessToken($userId)
    {
        $random = \Yii::$app->security->generateRandomString(64);
        $u = base64_encode($userId);
        $checksum = self::createSignature($userId);
        return $checksum."/".$random."/".$u;
    }

    public static function restoreUserIdFromToken($token)
    {
        $parts = explode("/", $token);
        if (count($parts) != 3)
            throw new BadRequestHttpException("Invalid token.");
        $userId = base64_decode($parts[2]);
        if (!self::checkSignature($parts[0], $userId))
            throw new BadRequestHttpException("Invalid token.");
        return $userId;
    }

}