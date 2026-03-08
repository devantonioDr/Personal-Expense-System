<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Usuarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="usuario-view box box-primary">
    <div class="box-header">
        <?= Html::a('Editar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-flat']) ?>
        <?= Html::a('Volver', ['index'], ['class' => 'btn btn-default btn-flat']) ?>
    </div>
    <div class="box-body table-responsive no-padding">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'username',
                'email',
                'user_rol',
                [
                    'attribute' => 'status',
                    'value' => $model->status == \common\models\User::STATUS_ACTIVE ? 'Activo' : 'Inactivo',
                ],
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
