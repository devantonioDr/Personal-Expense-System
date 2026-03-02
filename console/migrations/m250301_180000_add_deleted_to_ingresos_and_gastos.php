<?php

use yii\db\Migration;

/**
 * Añade columna deleted (soft delete) a ingresos y gastos.
 */
class m250301_180000_add_deleted_to_ingresos_and_gastos extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%ingresos}}', 'deleted', $this->tinyInteger(1)->notNull()->defaultValue(0));
        $this->addColumn('{{%gastos}}', 'deleted', $this->tinyInteger(1)->notNull()->defaultValue(0));
    }

    public function safeDown()
    {
        $this->dropColumn('{{%ingresos}}', 'deleted');
        $this->dropColumn('{{%gastos}}', 'deleted');
    }
}
