<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\VonageConfig */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Vonage Configs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vonage-config-view box box-primary">
    <div class="box-header">
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-flat']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
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
                'guId',
                'vonage_app_id',
                'test_phone',
                'private_key',
                'numbers',
            ],
        ]) ?>
    </div>
</div>
