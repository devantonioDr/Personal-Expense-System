<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Proyecto\Proyecto */

$this->title = $model->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Proyectos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="proyecto-view box box-primary">
    <div class="box-header">
        <?= Html::a('Editar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-flat']) ?>
        <?= Html::a('Eliminar', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger btn-flat',
            'data' => [
                'confirm' => '¿Está seguro de que desea eliminar este proyecto?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Volver', ['index'], ['class' => 'btn btn-default btn-flat']) ?>
    </div>
    <div class="box-body table-responsive no-padding">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'nombre',
                [
                    'attribute' => 'created_at',
                    'format' => ['date', 'php:d/m/Y H:i'],
                ],
                [
                    'attribute' => 'updated_at',
                    'format' => ['date', 'php:d/m/Y H:i'],
                ],
            ],
        ]) ?>
    </div>
</div>
