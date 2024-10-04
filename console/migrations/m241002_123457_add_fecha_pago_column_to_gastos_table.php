<?php

use yii\db\Migration;

/**
 * Handles adding column `fecha_pago` to table `gastos`.
 */
class m241002_123457_add_fecha_pago_column_to_gastos_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Paso 1: Agregar la columna fecha_pago permitiendo valores NULL temporalmente
        $this->addColumn('{{%gastos}}', 'fecha_pago', $this->date()->null()->after('monto'));

        // Paso 2: Actualizar los valores de fecha_pago con los valores convertidos de created_at
        $this->execute("
            UPDATE {{%gastos}} 
            SET fecha_pago = FROM_UNIXTIME(created_at, '%Y-%m-%d')
            WHERE created_at IS NOT NULL
        ");

        // Paso 3: Ahora cambiar la columna para que sea NOT NULL (si no hay registros con NULL)
        $this->alterColumn('{{%gastos}}', 'fecha_pago', $this->date()->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Eliminar la columna fecha_pago
        $this->dropColumn('{{%gastos}}', 'fecha_pago');
    }
}
