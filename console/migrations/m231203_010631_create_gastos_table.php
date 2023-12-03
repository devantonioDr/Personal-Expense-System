<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%gastos}}`.
 */
class m231203_010631_create_gastos_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%gastos}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'descripcion' => $this->string()->notNull(),
            'monto' => $this->decimal(10, 2)->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        // Agregar la clave foránea
        $this->addForeignKey(
            'fk-gastos-user_id',
            '{{%gastos}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%gastos}}');
    }
}
