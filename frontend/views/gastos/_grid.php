<?php

use common\models\Gastos\CategoriasGastos;
use yii\grid\GridView;
use yii\helpers\Html;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

$categorias = CategoriasGastos::find()->all();
?>
<?= GridView::widget([
    'id' => 'grid-gastos',

    'dataProvider' => $dataProvider,
    // 'filterModel' => $searchModel,
    'layout' => "{items}\n{summary}\n{pager}",
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],

        'descripcion',
        [
            'label' => "Categoria",
            'attribute' => 'categoria_nombre',
            'format' => 'raw', // Permite HTML en la celda
            'value' => function ($model) use ($categorias) {
                return Select2::widget([
                    'name' => 'categoria_id', // Nombre del campo
                    'value' => $model->categoria_id, // Valor actual (el id de la categoría seleccionada)
                    'data' => ArrayHelper::map($categorias, 'id', 'nombre'), // Las opciones del select
                    'options' => [
                        'class' => 'form-control categoria-select',
                        'data-id' => $model->id, // ID del gasto
                        'placeholder' => 'Seleccionar categoría', // Placeholder para la opción por defecto
                    ],
                    'pluginOptions' => [
                        'allowClear' => true, // Permite limpiar la selección
                    ],
                ]);
            },
        ],
        [
            'attribute' => 'monto',
            'value' => function ($model) {
                return number_format($model->monto, 2);
            },
        ],
        'fecha_pago:date',
        'created_at:datetime',
        // 'updated_at',

        ['class' => 'yii\grid\ActionColumn'],
    ],
]); ?>