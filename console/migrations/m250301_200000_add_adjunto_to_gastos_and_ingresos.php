<?php

use yii\db\Migration;

/**
 * Añade columna adjunto (ruta de foto) a gastos e ingresos.
 */
class m250301_200000_add_adjunto_to_gastos_and_ingresos extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%gastos}}', 'adjunto', $this->string(512)->null());
        $this->addColumn('{{%ingresos}}', 'adjunto', $this->string(512)->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%gastos}}', 'adjunto');
        $this->dropColumn('{{%ingresos}}', 'adjunto');
    }
}
