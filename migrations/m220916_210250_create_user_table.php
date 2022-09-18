<?php

use yii\db\Migration;

/**
 * Handles the creation of tables `user` and 'non-activated_user_code'.
 */
class m220916_210250_create_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'surname' => $this->string(),
            'name' => $this->string(),
            'email' => $this->string()->notNull()->unique(),
            'password' => $this->string()->notNull(),
            'auth_key' => $this->string(),
            'access_token' => $this->string(),
            'is_activated' => $this->boolean()->defaultValue(false),
        ]);

        $this->createTable('{{%activation_code}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'code' => $this->string(64)->unique()->notNull(),
        ]);

        $this->createIndex(
            'idx-activation_code-user_id',
            '{{%activation_code}}',
            'user_id'
        );

        $this->addForeignKey(
            'fk-activation_code-user_id',
            '{{%activation_code}}',
            'user_id',
            '{{%user}}',
            'id',
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-activation_code-user_id', '{{%activation_code}}');
        $this->dropIndex('idx-activation_code-user_id', '{{%activation_code}}');
        $this->dropTable('{{%activation_code}}');
        $this->dropTable('{{%user}}');
    }
}
