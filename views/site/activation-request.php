<?php
/** @var ar $message array */


use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Profile';
?>
    <h1>
        Требуется активация аккаунта
    </h1>
    Для дальнейшего использования Вашего профиля необходимо произвести его активацию, перейдя по ссылке отправленной на Ваш E-mail.
    <br/>
<?=
Html::a('Выйти из профиля', [Url::to('logout')], ['class' => 'link logout-link', 'data-method' => 'post'])
?>