<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\GastosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $gastoCalculator common\components\GastoCalculator */

$this->title = 'Gastos';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php echo $this->render('_top_header', [
    'hoy' => $gastoCalculator->calcularGastoDiaActual(),
    'mesActual' => $gastoCalculator->calcularGastoMesActual()
]); ?>


<div class="gastos-index box box-primary">
    <div class="box-header with-border">
        <?= Html::a('Registrar Gasto', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <div class="box-body table-responsive no-padding">
        <?php // echo $this->render('_search', ['model' => $searchModel]); 
        ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            // 'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'descripcion',
                'monto',
                'created_at:datetime',
                // 'updated_at',

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>
</div>