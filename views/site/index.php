<?php

use app\models\RegistrationForm;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/* @var $model RegistrationForm */


$this->title = 'Registration';
?>
    <h1>Регистрация</h1>
<?php $form = ActiveForm::begin([
    'id' => 'registration-form',
    'options' => ['class' => 'form']
]) ?>
<?= $form->field($model, 'surname') ?>
<?= $form->field($model, 'name') ?>
<?= $form->field($model, 'email') ?>
<?= $form->field($model, 'password')->passwordInput() ?>
<?= $form->field($model, 'password_repeat')->passwordInput() ?>
    </br>
    <div class="form-group">
        <?= Html::submitButton('Зарегистрироваться', ['class' => 'btn btn-primary form-control', 'name' => 'sign-up-button']) ?>
    </div>
<?php ActiveForm::end(); ?>
    </br>
<?php
echo Html::a('У меня уже есть аккаунт', [Url::to('login')], ['class' => 'link login-link'])
?>