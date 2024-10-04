<?php

use yii\db\Migration;

/**
 * Class m241002_123458_insert_categorias_gastos
 */
class m241002_123458_insert_categorias_gastos extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $time = time(); // Obtener el timestamp actual para created_at y updated_at

        $this->batchInsert('{{%categorias_gastos}}', ['nombre', 'descripcion', 'created_at', 'updated_at'], [
            ['Alimentación', 'Supermercado, restaurantes, comida a domicilio', $time, $time],
            ['Vivienda', 'Alquiler, hipoteca, mantenimiento, reparaciones', $time, $time],
            ['Servicios Públicos', 'Electricidad, agua, gas, internet, teléfono', $time, $time],
            ['Transporte', 'Gasolina, transporte público, mantenimiento de coche', $time, $time],
            ['Salud', 'Seguros médicos, medicamentos, consultas', $time, $time],
            ['Educación', 'Matrículas, libros, cursos online', $time, $time],
            ['Entretenimiento y Ocio', 'Cine, streaming, videojuegos, deportes', $time, $time],
            ['Ropa y Calzado', 'Ropa diaria, calzado, accesorios', $time, $time],
            ['Seguros', 'Seguro de vida, seguro de automóvil, seguro del hogar', $time, $time],
            ['Ahorros e Inversiones', 'Ahorros mensuales, inversiones, jubilación', $time, $time],
            ['Mascotas', 'Alimentos para mascotas, veterinario, accesorios', $time, $time],
            ['Hogar y Decoración', 'Muebles, decoración, electrodomésticos', $time, $time],
            ['Deudas', 'Pagos de tarjetas de crédito, préstamos personales', $time, $time],
            ['Regalos y Donaciones', 'Regalos, donaciones a organizaciones benéficas', $time, $time],
            ['Impuestos', 'Impuestos sobre la renta, bienes inmuebles', $time, $time],
            ['Tecnología', 'Teléfono móvil, dispositivos electrónicos', $time, $time],
            ['Belleza y Cuidado Personal', 'Cuidado del cabello, productos de belleza', $time, $time],
            ['Hobbies y Actividades Personales', 'Materiales para hobbies, clases extracurriculares', $time, $time],
            ['Finanzas Personales', 'Ahorros, inversiones, criptomonedas', $time, $time],
            ['Bienestar Emocional', 'Libros de autoayuda, terapia psicológica', $time, $time],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Eliminar todas las categorías insertadas en esta migración
        $this->delete('{{%categorias_gastos}}', [
            'nombre' => [
                'Alimentación',
                'Vivienda',
                'Servicios Públicos',
                'Transporte',
                'Salud',
                'Educación',
                'Entretenimiento y Ocio',
                'Ropa y Calzado',
                'Seguros',
                'Ahorros e Inversiones',
                'Mascotas',
                'Hogar y Decoración',
                'Deudas',
                'Regalos y Donaciones',
                'Impuestos',
                'Tecnología',
                'Belleza y Cuidado Personal',
                'Hobbies y Actividades Personales',
                'Finanzas Personales',
                'Bienestar Emocional'
            ]
        ]);
    }
}
