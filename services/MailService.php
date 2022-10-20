<?php

namespace app\services;

use Yii;


class MailService
{

    public function sendActivationMessage($activation_code, $email, $surname, $name)
    {
        return Yii::$app->mailer->compose('layouts/activationEmail', ['activation_code' => $activation_code])
            ->setFrom([Yii::$app->params['senderEmail'] => Yii::$app->name . '(отправлено автоматически)'])
            ->setTo($email)
            ->setSubject('Активация профиля для ' . $surname . ' ' . $name)
            ->send();
    }
}