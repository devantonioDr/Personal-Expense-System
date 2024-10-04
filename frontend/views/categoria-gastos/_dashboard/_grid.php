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
        'total_gastos',

    ],
]); ?>