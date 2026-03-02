<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Proyectos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="proyecto-index box box-primary">
    <div class="box-header with-border">
        <?= Html::a('Nuevo proyecto', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <div class="box-body table-responsive no-padding">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'nombre',
                [
                    'attribute' => 'user_id',
                    'label' => 'Creado por',
                    'value' => function ($model) {
                        return $model->user ? $model->user->username : '';
                    },
                ],
                [
                    'label' => 'Usuarios autorizados',
                    'value' => function ($model) {
                        $users = $model->usuariosConPermiso;
                        if (empty($users)) {
                            return Html::tag('span', '—', ['class' => 'text-muted']);
                        }
                        return implode(', ', array_map(function ($u) {
                            return Html::encode($u->username);
                        }, $users));
                    },
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'created_at',
                    'format' => ['date', 'php:d/m/Y H:i'],
                ],
                [
                    'attribute' => 'updated_at',
                    'format' => ['date', 'php:d/m/Y H:i'],
                ],
                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>
</div>
