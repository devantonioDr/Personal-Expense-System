<?php

use yii\db\Migration;

/**
 * Class m250525_211800_make_fields_nullable
 */
class m250525_211800_make_fields_nullable extends Migration
{
   public function safeUp()
    {
        // Modificar columnas para permitir NULL
        $this->alterColumn('categorias_gastos', 'color_etiqueta', $this->string()->null());
        $this->alterColumn('categorias_gastos', 'created_at', $this->integer()->null());
        $this->alterColumn('categorias_gastos', 'updated_at', $this->integer()->null());
    }

    public function safeDown()
    {
        // Restaurar columnas como NOT NULL (puedes ajustar tipos y valores por defecto según necesidad)
        $this->alterColumn('categorias_gastos', 'color_etiqueta', $this->string()->notNull());
        $this->alterColumn('categorias_gastos', 'created_at', $this->integer()->notNull());
        $this->alterColumn('categorias_gastos', 'updated_at', $this->integer()->notNull());
    }
}
