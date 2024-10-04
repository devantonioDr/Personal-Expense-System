<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\Gastos\CategoriasGastosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Categorias';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="categorias-gastos-index box box-primary">
    <div class="box-header with-border">
        <?= Html::a('Create Categorias Gastos', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <div class="box-body table-responsive no-padding">
        <?php // echo $this->render('_search', ['model' => $searchModel]); 
        ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'nombre',
                [
                    'label' => 'Total Gastos',
                    'attribute'=>'gastos_count',
                    'value' => function ($model) {
                        return $model->gastos_count; // Mostrar el conteo de los gastos
                    },
                    'headerOptions' => ['style' => 'text-align: center;'], // Opcional: centrar el encabezado
                    'contentOptions' => ['style' => 'text-align: center;'], // Opcional: centrar el contenido
                    'enableSorting' => true, // Habilitar el ordenamiento para esta columna
                ],
                'descripcion',
                'created_at:date',

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>

    </div>
</div>