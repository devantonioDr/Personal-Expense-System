<?php

use yii\db\Migration;

/**
 * Añade columna deleted a proyecto para borrado lógico.
 */
class m250301_120000_add_deleted_to_proyecto extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%proyecto}}', 'deleted', $this->tinyInteger(1)->notNull()->defaultValue(0));
    }

    public function safeDown()
    {
        $this->dropColumn('{{%proyecto}}', 'deleted');
    }
}
