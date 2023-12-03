<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\ComboUser */

$this->title = 'Actualizar: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Combo Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="combo-user-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
