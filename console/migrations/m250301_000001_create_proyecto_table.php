<?php

use yii\db\Migration;

/**
 * Crea la tabla `{{%proyecto}}`.
 */
class m250301_000001_create_proyecto_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%proyecto}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'nombre' => $this->string(255)->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx-proyecto-user_id', '{{%proyecto}}', 'user_id');
        $this->addForeignKey(
            'fk-proyecto-user_id',
            '{{%proyecto}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-proyecto-user_id', '{{%proyecto}}');
        $this->dropIndex('idx-proyecto-user_id', '{{%proyecto}}');
        $this->dropTable('{{%proyecto}}');
    }
}
