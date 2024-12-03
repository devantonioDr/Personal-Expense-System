<?php

use yii\db\Migration;

/**
 * Class m241125_131714_ingreso
 */
class m241125_131714_ingreso extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Crear tabla ingresos_categoria
        $this->createTable('{{%ingresos_categoria}}', [
            'id' => $this->primaryKey(),
            'nombre' => $this->string(255)->notNull(),
            'descripcion' => $this->text()->null(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        // Crear tabla ingresos
        $this->createTable('{{%ingresos}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'descripcion' => $this->string(255)->notNull(),
            'monto' => $this->decimal(10, 2)->notNull(),
            'fecha_pago' => $this->date()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'categoria_id' => $this->integer()->null()->defaultValue(null),
        ]);

        // Crear índice y clave foránea para user_id
        $this->createIndex(
            'idx-ingresos-user_id',
            '{{%ingresos}}',
            'user_id'
        );
        $this->addForeignKey(
            'fk-ingresos-user_id',
            '{{%ingresos}}',
            'user_id',
            '{{%user}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        // Crear índice y clave foránea para categoria_id
        $this->createIndex(
            'idx-ingresos-categoria_id',
            '{{%ingresos}}',
            'categoria_id'
        );
        $this->addForeignKey(
            'fk-ingresos-categoria_id',
            '{{%ingresos}}',
            'categoria_id',
            '{{%ingresos_categoria}}',
            'id',
            'NO ACTION',
            'SET NULL'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Eliminar claves foráneas e índices
        $this->dropForeignKey('fk-ingresos-user_id', '{{%ingresos}}');
        $this->dropIndex('idx-ingresos-user_id', '{{%ingresos}}');

        $this->dropForeignKey('fk-ingresos-categoria_id', '{{%ingresos}}');
        $this->dropIndex('idx-ingresos-categoria_id', '{{%ingresos}}');

        // Eliminar tabla ingresos
        $this->dropTable('{{%ingresos}}');

        // Eliminar tabla ingresos_categoria
        $this->dropTable('{{%ingresos_categoria}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m241125_131714_ingreso cannot be reverted.\n";

        return false;
    }
    */
}
