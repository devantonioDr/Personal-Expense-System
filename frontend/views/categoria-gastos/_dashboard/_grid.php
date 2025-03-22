<?php

use yii\data\ArrayDataProvider;
use yii\grid\GridView;
?>
<?= GridView::widget([
    'id' => 'grid-gastos',
    // 'dataProvider' => $dataProvider,
    'dataProvider' => new ArrayDataProvider([
        'allModels' => $data, // Use the extracted data
        'sort' => ['attributes' => array_merge(array_keys($searchModel->getAttributes()), ['total_gastos'])],
    ]),
    'layout' => "{items}\n{summary}\n{pager}",
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],

        'nombre',
        'descripcion',
        [
            'attribute' => 'total_gastos',
            'value' => function ($model) {
                return number_format($model->total_gastos); // Mostrar el conteo de los gastos
            },
            'headerOptions' => ['style' => 'text-align: center;'], // Opcional: centrar el encabezado
            'contentOptions' => ['style' => 'text-align: center;'], // Opcional: centrar el contenido
            'enableSorting' => true, // Habilitar el ordenamiento para esta columna
        ],

    ],
]); ?>