<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */

/** @var app\models\User $model */

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap5\Modal;

$this->title = 'Profile';
?>
<h1>Профиль</h1>
<?php $form = ActiveForm::begin([
    'id' => 'login-form',
    'options' => ['class' => 'form']
]) ?>
<?= $form->field($model, 'surname') ?>
<?= $form->field($model, 'name') ?>
<?= $form->field($model, 'email')->textInput(['disabled' => true]) ?>
</br>
<div class="form-group">
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary form-control', 'name' => 'sign-up-button']) ?>
</div>
<?php ActiveForm::end(); ?>
</br>
</br>
<?php
echo Html::a('Выйти из профиля', [Url::to('logout')], ['class' => 'link logout-link', 'data-method' => 'post'])
?>

<?php Modal::begin([
    'bodyOptions' => ['class' => 'centr-text'],
    'title' => 'Удаление профиля',
    'toggleButton' => ['label' => 'Удалить профиль', 'class' => 'btn btn-danger float-right'],
    'footer' =>
        Html::a(
            Html::button('Да', ['class' => 'btn btn-danger inner-btn']),
            Url::to('delete-profile'), ['class' => 'left-btn']) . ' '
        . Html::button('Отмена', ['class' => 'btn btn-secondary right-btn', 'id' => 'cancel-button', 'data-bs-dismiss' => 'modal']),

]);

echo 'Вы действительно хотите удалить профиль?';

Modal::end(); ?>
