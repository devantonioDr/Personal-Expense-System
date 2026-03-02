<?php

use yii\db\Migration;

/**
 * Añade columna user_rol a user (varchar). Valor por defecto: operador.
 */
class m250301_160000_add_user_rol_to_user extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'user_rol', $this->string(50)->notNull()->defaultValue('operador'));
    }

    public function safeDown()
    {
        $this->dropColumn('{{%user}}', 'user_rol');
    }
}
