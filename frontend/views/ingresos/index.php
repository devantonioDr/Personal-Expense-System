<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\Ingresos\IngresosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $proyecto common\models\Proyecto\Proyecto|null */

$this->title = 'Ingresos';
if (isset($proyecto) && $proyecto) {
    $this->params['breadcrumbs'][] = ['label' => $proyecto->nombre, 'url' => ['/dashboard/index', 'proyecto_id' => $proyecto->id]];
}
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <!-- Info Box for Ingreso Mensual -->
    <div class="col-md-6">
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

<?php echo $this->render('_search', [
    'model' => $searchModel,
    'proyecto_id' => $proyecto->id ?? $searchModel->proyecto_id ?? null,
]);
?>

<div class="ingresos-index box box-primary">


    <div class="box-header with-border">
        <?= Html::a('Crear ingreso', array_merge(['create'], !empty($searchModel->proyecto_id) ? ['proyecto_id' => $searchModel->proyecto_id] : []), ['class' => 'btn btn-success btn-flat']) ?>
    </div>


    <div class="box-body table-responsive no-padding">

        <?= GridView::widget([
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
                            return Html::a('📷', $model->getAdjuntoUrl(), ['class' => 'adjunto-lightbox-trigger', 'title' => 'Ver comprobante']);
                        }
                        return '';
                    },
                ],
                [
                    'attribute' => 'monto',
                    'value' => function ($model) {
                        return number_format($model->monto, 2);
                    },
                ],
                'fecha_pago',
                [
                    'attribute' => 'created_by',
                    'label' => 'Creado por',
                    'value' => function ($model) {
                        return $model->createdByUser ? $model->createdByUser->username : '';
                    },
                ],
                // 'created_at',
                // 'updated_at',
                // 'categoria_id',

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>
</div>