<?php

namespace app\models;

use yii\base\Model;
use app\models\User;

/**
 * Signup form
 */
class RegistrationForm extends Model
{
    public $surname;
    public $name;
    public $email;
    public $password;
    public $password_repeat;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['surname', 'name', 'email'], 'trim'],
            [['email', 'password'], 'required', 'message' => 'Поле обязательно для заполнения'],
            ['email', 'email', 'message' => 'Введенный E-mail не соответствует формату E-mail'],
            ['email', 'unique', 'targetClass' => 'app\models\User', 'message' => 'Этот E-mail уже зарегистрирован'],
            [['surname', 'name', 'password'], 'string', 'min' => 2, 'max' => 255],
            ['password_repeat', 'compare', 'compareAttribute' => 'password', 'message' => 'Пароли не совпадают'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'surname' => 'Фамилия',
            'name' => 'Имя',
            'email' => 'Е-mail',
            'password' => 'Пароль',
            'password_repeat' => 'Подтверждение пароля'
        ];
    }

    /**
     * Signs user up.
     *
     * @return bool|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        $user = new User();
        $user->surname = $this->surname;
        $user->name = $this->name;
        $user->email = $this->email;
        $user->is_activated = false;
        $user->setPassword($this->password);
        return $user->save();
    }
}

