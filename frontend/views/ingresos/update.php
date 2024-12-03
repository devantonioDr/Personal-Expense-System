<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Ingresos\Ingresos */

$this->title = 'Update Ingreso: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Ingresos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ingresos-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
