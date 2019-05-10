<?php

namespace api\models;

use api\helpers\SecurityHelper;
use yii\base\Model;

class LoginForm extends Model
{
    private $_user;
    public $lat;
    public $lon;
    public $address;
    public $username;
    public $password;

    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            [['address', 'username', 'password'], 'trim'],
            ['password', 'validatePassword'],
        ];
    }
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Tên đăng nhập hoặc mật khẩu không đúng!');
            }
        }
    }
    public function login()
    {

        if ($this->validate()) {
            $user = $this->getUser();

            if (empty($user)) {
                return false;
            }
            \Yii::$app->user->login($user);
            $this->_user->access_token = SecurityHelper::generateAccessToken($this->_user->id);
            $this->_user->save(false);

            return true;
        }

        return false;
    }

    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::find()
                ->where([
                    'username' => ($this->username)
                ])
                ->one();
        }
        return $this->_user;
    }
}
