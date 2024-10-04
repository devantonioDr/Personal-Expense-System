<?php

use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\Gastos\GastosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $gastoCalculator common\models\Gasto\GastoCalculatorGastoCalculator */

$this->title = 'Dashboard Gastos Por Categorias';
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
    .kv-plugin-loading {
        display: none;
    }
</style>

<?php echo $this->render('_top_header', [
    'hoy' => $gastoCalculator->calcularGastoDiaActual(),
    'mesActual' => $gastoCalculator->calcularGastoMesActual()
]); ?>

<?php echo $this->render('_search', ['model' => $searchModel]); ?>

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">Gastos Por Categorias</h3>
    </div>
    <div class="panel-body">
        <?php echo $this->render("_pie_chart", ['data' => $data]); ?>
    </div>
    <div class="box-footer  no-padding">
        <?php Pjax::begin(); ?> <!-- Begin Pjax Container -->
        <?php echo $this->render("_grid", [
            'data' => $data,
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel
        ]); ?>
        <?php Pjax::end(); ?> <!-- End Pjax Container -->
    </div>

</div>