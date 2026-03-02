<?php

use yii\db\Migration;

/**
 * Crea la tabla proyecto_usuario (usuarios con permiso para usar un proyecto).
 */
class m250301_150000_create_proyecto_usuario_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%proyecto_usuario}}', [
            'id' => $this->primaryKey(),
            'proyecto_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx-proyecto_usuario-proyecto_id', '{{%proyecto_usuario}}', 'proyecto_id');
        $this->createIndex('idx-proyecto_usuario-user_id', '{{%proyecto_usuario}}', 'user_id');
        $this->createIndex('uq-proyecto_usuario-proyecto_user', '{{%proyecto_usuario}}', ['proyecto_id', 'user_id'], true);

        $this->addForeignKey(
            'fk-proyecto_usuario-proyecto_id',
            '{{%proyecto_usuario}}',
            'proyecto_id',
            '{{%proyecto}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-proyecto_usuario-user_id',
            '{{%proyecto_usuario}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-proyecto_usuario-user_id', '{{%proyecto_usuario}}');
        $this->dropForeignKey('fk-proyecto_usuario-proyecto_id', '{{%proyecto_usuario}}');
        $this->dropTable('{{%proyecto_usuario}}');
    }
}
