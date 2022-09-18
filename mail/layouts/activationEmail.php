<?php
/**
 * @var $activation_code string
 */

use yii\helpers\Html;

echo 'Для активации аккаунта перейдите <b>' .
    Html::a('по этой ссылке.', Yii::$app->urlManager->createAbsoluteUrl(['activate-profile', 'activation_code' => $activation_code])) . '</b>';
