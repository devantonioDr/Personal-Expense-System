<?php

use yii\db\Migration;

/**
 * Añade proyecto_id a gastos, ingresos e ingresos_categoria.
 */
class m250301_000002_add_proyecto_id_to_related_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%gastos}}', 'proyecto_id', $this->integer()->null());
        $this->createIndex('idx-gastos-proyecto_id', '{{%gastos}}', 'proyecto_id');
        $this->addForeignKey(
            'fk-gastos-proyecto_id',
            '{{%gastos}}',
            'proyecto_id',
            '{{%proyecto}}',
            'id',
            'SET NULL',
            'CASCADE'
        );

        $this->addColumn('{{%ingresos}}', 'proyecto_id', $this->integer()->null());
        $this->createIndex('idx-ingresos-proyecto_id', '{{%ingresos}}', 'proyecto_id');
        $this->addForeignKey(
            'fk-ingresos-proyecto_id',
            '{{%ingresos}}',
            'proyecto_id',
            '{{%proyecto}}',
            'id',
            'SET NULL',
            'CASCADE'
        );

        $this->addColumn('{{%ingresos_categoria}}', 'proyecto_id', $this->integer()->null());
        $this->createIndex('idx-ingresos_categoria-proyecto_id', '{{%ingresos_categoria}}', 'proyecto_id');
        $this->addForeignKey(
            'fk-ingresos_categoria-proyecto_id',
            '{{%ingresos_categoria}}',
            'proyecto_id',
            '{{%proyecto}}',
            'id',
            'SET NULL',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-gastos-proyecto_id', '{{%gastos}}');
        $this->dropIndex('idx-gastos-proyecto_id', '{{%gastos}}');
        $this->dropColumn('{{%gastos}}', 'proyecto_id');

        $this->dropForeignKey('fk-ingresos-proyecto_id', '{{%ingresos}}');
        $this->dropIndex('idx-ingresos-proyecto_id', '{{%ingresos}}');
        $this->dropColumn('{{%ingresos}}', 'proyecto_id');

        $this->dropForeignKey('fk-ingresos_categoria-proyecto_id', '{{%ingresos_categoria}}');
        $this->dropIndex('idx-ingresos_categoria-proyecto_id', '{{%ingresos_categoria}}');
        $this->dropColumn('{{%ingresos_categoria}}', 'proyecto_id');
    }
}
