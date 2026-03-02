<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Ingresos\Ingresos */
/* @var $proyecto common\models\Proyecto\Proyecto|null */

$this->title = 'Ingreso #' . $model->id;
if (isset($proyecto) && $proyecto) {
    $this->params['breadcrumbs'][] = ['label' => $proyecto->nombre, 'url' => ['/dashboard/index', 'proyecto_id' => $proyecto->id]];
    $this->params['breadcrumbs'][] = ['label' => 'Ingresos', 'url' => ['index', 'proyecto_id' => $proyecto->id]];
} else {
    $this->params['breadcrumbs'][] = ['label' => 'Ingresos', 'url' => ['index']];
}
$this->params['breadcrumbs'][] = $this->title;
$this->registerCssFile(\Yii::getAlias('@web/css/adjunto-foto.css'), ['depends' => [\yii\web\YiiAsset::class]]);
?>
<div class="ingresos-view box box-primary">
    <div class="box-header">
        <?php
        $updateUrl = ['update', 'id' => $model->id];
        $deleteUrl = ['delete', 'id' => $model->id];
        if (!empty($model->proyecto_id)) {
            $updateUrl['proyecto_id'] = $model->proyecto_id;
            $deleteUrl['proyecto_id'] = $model->proyecto_id;
        }
        ?>
        <?= Html::a('Actualizar', $updateUrl, ['class' => 'btn btn-primary btn-flat']) ?>
        <?= Html::a('Borrar', $deleteUrl, [
            'class' => 'btn btn-danger btn-flat',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </div>
    <div class="box-body table-responsive no-padding">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'user_id',
                'descripcion',
                'monto',
                'fecha_pago',
                'created_at:datetime',
                'updated_at:datetime',
                'categoria_id',
            ],
        ]) ?>
        <?php if ($model->adjunto && $model->getAdjuntoUrl()): ?>
        <div class="adjunto-comprobante">
            <strong>Comprobante / Foto</strong>
            <a href="<?= Html::encode($model->getAdjuntoUrl()) ?>" class="adjunto-lightbox-trigger">
                <img src="<?= Html::encode($model->getAdjuntoUrl()) ?>" alt="Comprobante" />
            </a>
            <a href="<?= Html::encode($model->getAdjuntoUrl()) ?>" target="_blank" rel="noopener">Abrir en nueva pestaña</a>
        </div>
        <?php endif; ?>
    </div>
</div>
