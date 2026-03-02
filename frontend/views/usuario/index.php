<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Usuarios';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="usuario-index box box-primary">
    <div class="box-header with-border">
        <?= Html::a('Nuevo usuario', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <div class="box-body table-responsive no-padding">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'id',
                'username',
                'email',
                'user_rol',
                [
                    'attribute' => 'status',
                    'value' => function ($model) {
                        return $model->status == \common\models\User::STATUS_ACTIVE ? 'Activo' : 'Inactivo';
                    },
                ],
                [
                    'attribute' => 'created_at',
                    'format' => ['date', 'php:d/m/Y H:i'],
                ],
                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>
</div>
