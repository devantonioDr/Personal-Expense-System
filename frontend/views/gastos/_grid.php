<?php

use yii\grid\GridView;
use yii\helpers\Html;
use kartik\select2\Select2;

/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel common\models\Gastos\GastosSearch */
/* @var $categorias array id => nombre */

$categorias = $categorias ?? [];
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
            'label' => '',
            'format' => 'raw',
            'value' => function ($model) {
                if ($model->adjunto && $model->getAdjuntoUrl()) {
                    return Html::a('📷', $model->getAdjuntoUrl(), ['class' => 'adjunto-lightbox-trigger', 'title' => 'Ver comprobante', 'data-pjax' => '0']);
                }
                return '';
            },
        ],
        [
            'label' => "Categoria",
            'attribute' => 'categoria_nombre',
            'format' => 'raw', // Permite HTML en la celda
            'value' => function ($model) use ($categorias) {
                return Select2::widget([
                    'name' => 'categoria_id',
                    'value' => $model->categoria_id,
                    'data' => $categorias,
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
        [
            'attribute' => 'created_by',
            'label' => 'Creado por',
            'value' => function ($model) {
                return $model->createdByUser ? $model->createdByUser->username : '';
            },
        ],
        'created_at:datetime',
        // 'updated_at',

        ['class' => 'yii\grid\ActionColumn'],
    ],
]); ?>