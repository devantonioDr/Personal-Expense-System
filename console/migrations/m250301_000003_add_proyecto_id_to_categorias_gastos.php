<?php

use yii\db\Migration;

/**
 * Añade proyecto_id a categorias_gastos.
 */
class m250301_000003_add_proyecto_id_to_categorias_gastos extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%categorias_gastos}}', 'proyecto_id', $this->integer()->null());
        $this->createIndex('idx-categorias_gastos-proyecto_id', '{{%categorias_gastos}}', 'proyecto_id');
        $this->addForeignKey(
            'fk-categorias_gastos-proyecto_id',
            '{{%categorias_gastos}}',
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
        $this->dropForeignKey('fk-categorias_gastos-proyecto_id', '{{%categorias_gastos}}');
        $this->dropIndex('idx-categorias_gastos-proyecto_id', '{{%categorias_gastos}}');
        $this->dropColumn('{{%categorias_gastos}}', 'proyecto_id');
    }
}
