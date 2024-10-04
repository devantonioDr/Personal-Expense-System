<?php

use yii\db\Migration;

/**
 * Class m241003_170958_add_color_etiqueta_and_update_categories
 */
class m241003_170958_add_color_etiqueta_and_update_categories extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Agregar la columna 'color_etiqueta'
        $this->addColumn('{{%categorias_gastos}}', 'color_etiqueta', $this->string(7)->notNull()->after('descripcion'));

        // Tiempo actual para las columnas created_at y updated_at
        $time = time();

        // Actualizar las categorías existentes con colores congruentes
        $this->update('{{%categorias_gastos}}', ['color_etiqueta' => '#FF5733'], ['nombre' => 'Alimentación']);
        $this->update('{{%categorias_gastos}}', ['color_etiqueta' => '#3498DB'], ['nombre' => 'Vivienda']);
        $this->update('{{%categorias_gastos}}', ['color_etiqueta' => '#1ABC9C'], ['nombre' => 'Servicios Públicos']);
        $this->update('{{%categorias_gastos}}', ['color_etiqueta' => '#F39C12'], ['nombre' => 'Transporte']);
        $this->update('{{%categorias_gastos}}', ['color_etiqueta' => '#E74C3C'], ['nombre' => 'Salud']);
        $this->update('{{%categorias_gastos}}', ['color_etiqueta' => '#9B59B6'], ['nombre' => 'Educación']);
        $this->update('{{%categorias_gastos}}', ['color_etiqueta' => '#E67E22'], ['nombre' => 'Entretenimiento y Ocio']);
        $this->update('{{%categorias_gastos}}', ['color_etiqueta' => '#2ECC71'], ['nombre' => 'Ropa y Calzado']);
        $this->update('{{%categorias_gastos}}', ['color_etiqueta' => '#16A085'], ['nombre' => 'Seguros']);
        $this->update('{{%categorias_gastos}}', ['color_etiqueta' => '#2980B9'], ['nombre' => 'Ahorros e Inversiones']);
        $this->update('{{%categorias_gastos}}', ['color_etiqueta' => '#8E44AD'], ['nombre' => 'Mascotas']);
        $this->update('{{%categorias_gastos}}', ['color_etiqueta' => '#D35400'], ['nombre' => 'Hogar y Decoración']);
        $this->update('{{%categorias_gastos}}', ['color_etiqueta' => '#C0392B'], ['nombre' => 'Deudas']);
        $this->update('{{%categorias_gastos}}', ['color_etiqueta' => '#BDC3C7'], ['nombre' => 'Regalos y Donaciones']);
        $this->update('{{%categorias_gastos}}', ['color_etiqueta' => '#34495E'], ['nombre' => 'Impuestos']);
        $this->update('{{%categorias_gastos}}', ['color_etiqueta' => '#27AE60'], ['nombre' => 'Tecnología']);
        $this->update('{{%categorias_gastos}}', ['color_etiqueta' => '#F1C40F'], ['nombre' => 'Belleza y Cuidado Personal']);
        $this->update('{{%categorias_gastos}}', ['color_etiqueta' => '#E74C3C'], ['nombre' => 'Hobbies y Actividades Personales']);
        $this->update('{{%categorias_gastos}}', ['color_etiqueta' => '#95A5A6'], ['nombre' => 'Finanzas Personales']);
        $this->update('{{%categorias_gastos}}', ['color_etiqueta' => '#D35400'], ['nombre' => 'Bienestar Emocional']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Eliminar la columna 'color_etiqueta' en caso de revertir la migración
        $this->dropColumn('{{%categorias_gastos}}', 'color_etiqueta');
    }
}
