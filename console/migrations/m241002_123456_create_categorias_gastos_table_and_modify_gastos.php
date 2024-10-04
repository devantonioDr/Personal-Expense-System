<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%categorias_gastos}}` and modifies `gastos` table.
 */
class m241002_123456_create_categorias_gastos_table_and_modify_gastos extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Crear tabla categorias_gastos
        $this->createTable('{{%categorias_gastos}}', [
            'id' => $this->primaryKey(),
            'nombre' => $this->string(100)->notNull(),
            'descripcion' => $this->string(255),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        // Modificar la tabla gastos
        $this->addColumn('{{%gastos}}', 'categoria_id', $this->integer()->null());

        // Crear índice para la columna categoria_id en la tabla gastos
        $this->createIndex(
            'idx-gastos-categoria_id',
            '{{%gastos}}',
            'categoria_id'
        );

        // Agregar clave foránea para la relación gastos -> categorias_gastos
        $this->addForeignKey(
            'fk-gastos-categoria_id',
            '{{%gastos}}',
            'categoria_id',
            '{{%categorias_gastos}}',
            'id',
            'SET NULL', // Si se elimina una categoría, se establece en NULL
            'NO ACTION' // No se hace nada en actualizaciones
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Eliminar la clave foránea
        $this->dropForeignKey(
            'fk-gastos-categoria_id',
            '{{%gastos}}'
        );

        // Eliminar el índice de categoria_id
        $this->dropIndex(
            'idx-gastos-categoria_id',
            '{{%gastos}}'
        );

        // Eliminar la columna categoria_id de la tabla gastos
        $this->dropColumn('{{%gastos}}', 'categoria_id');

        // Eliminar la tabla categorias_gastos
        $this->dropTable('{{%categorias_gastos}}');
    }
}
