<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Gastos\CategoriasGastos */

$this->title = 'Update Categoria: ' . $model->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Categorias Gastos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="categorias-gastos-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
