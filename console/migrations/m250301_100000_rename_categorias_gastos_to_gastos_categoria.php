<?php

use yii\db\Migration;

/**
 * Renombra la tabla categorias_gastos a gastos_categoria.
 */
class m250301_100000_rename_categorias_gastos_to_gastos_categoria extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('fk-gastos-categoria_id', '{{%gastos}}');
        $this->renameTable('{{%categorias_gastos}}', '{{%gastos_categoria}}');
        $this->addForeignKey(
            'fk-gastos-categoria_id',
            '{{%gastos}}',
            'categoria_id',
            '{{%gastos_categoria}}',
            'id',
            'SET NULL',
            'NO ACTION'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-gastos-categoria_id', '{{%gastos}}');
        $this->renameTable('{{%gastos_categoria}}', '{{%categorias_gastos}}');
        $this->addForeignKey(
            'fk-gastos-categoria_id',
            '{{%gastos}}',
            'categoria_id',
            '{{%categorias_gastos}}',
            'id',
            'SET NULL',
            'NO ACTION'
        );
    }
}

