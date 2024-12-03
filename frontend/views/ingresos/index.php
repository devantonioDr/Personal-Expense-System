<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\IngresosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Ingresos';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <!-- Info Box for Ingreso Mensual -->
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-green">
            <div class="inner">
                <h3>
                    <?= Yii::$app->formatter->asCurrency($totalIngresoMes ?? 0, 'USD') ?>
                </h3>
                <p>Ingreso del Mes Actual</p>
            </div>
            <div class="icon">
                <i class="fa fa-money"></i>
            </div>
        </div>
    </div>
</div>

<?php echo $this->render('_search', ['model' => $searchModel]);
?>

<div class="ingresos-index box box-primary">


    <div class="box-header with-border">
        <?= Html::a('Create Ingresos', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
    </div>


    <div class="box-body table-responsive no-padding">

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            // 'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'descripcion',
                'monto',
                'fecha_pago',
                // 'created_at',
                // 'updated_at',
                // 'categoria_id',

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>
</div>