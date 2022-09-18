<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use app\models\User;

/**
 * This is the model class for table "activation_code".
 *
 * @property int $id
 * @property int $user_id
 * @property string $code
 */
class ActivationCode extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%activation_code}}';
    }

    /**
     * Returns User by user_id
     * @return ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

}