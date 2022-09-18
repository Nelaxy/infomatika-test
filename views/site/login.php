<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var int $reg */

/** @var app\models\LoginForm $model */

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Login';
?>
<?php
if ($reg === 1):?>
    На указанный E-mail отправлено письмо. Для активации профиля необходимо перейти по ссылке указаной в письме.
<?php
endif;
?>

    <h1>Авторизация</h1>
<?php $form = ActiveForm::begin([
    'id' => 'login-form',
    'options' => ['class' => 'form']
]) ?>
<?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>
<?= $form->field($model, 'password')->passwordInput() ?>
<?= $form->field($model, 'rememberMe')->checkbox() ?>
    </br>
    <div class="form-group">
        <?= Html::submitButton('Войти', ['class' => 'btn btn-primary form-control', 'name' => 'sign-up-button']) ?>
    </div>
<?php ActiveForm::end(); ?>
    </br>
<?php
echo Html::a('У меня нет аккаунта', [Url::to('index')], ['class' => 'link index-link'])
?>