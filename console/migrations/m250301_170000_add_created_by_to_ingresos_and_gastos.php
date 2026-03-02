<?php

use yii\db\Migration;

/**
 * Añade created_by a ingresos y gastos para registrar quién insertó cada registro.
 */
class m250301_170000_add_created_by_to_ingresos_and_gastos extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%ingresos}}', 'created_by', $this->integer()->null());
        $this->createIndex('idx-ingresos-created_by', '{{%ingresos}}', 'created_by');
        $this->addForeignKey(
            'fk-ingresos-created_by',
            '{{%ingresos}}',
            'created_by',
            '{{%user}}',
            'id',
            'SET NULL',
            'CASCADE'
        );

        $this->addColumn('{{%gastos}}', 'created_by', $this->integer()->null());
        $this->createIndex('idx-gastos-created_by', '{{%gastos}}', 'created_by');
        $this->addForeignKey(
            'fk-gastos-created_by',
            '{{%gastos}}',
            'created_by',
            '{{%user}}',
            'id',
            'SET NULL',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-gastos-created_by', '{{%gastos}}');
        $this->dropIndex('idx-gastos-created_by', '{{%gastos}}');
        $this->dropColumn('{{%gastos}}', 'created_by');

        $this->dropForeignKey('fk-ingresos-created_by', '{{%ingresos}}');
        $this->dropIndex('idx-ingresos-created_by', '{{%ingresos}}');
        $this->dropColumn('{{%ingresos}}', 'created_by');
    }
}
