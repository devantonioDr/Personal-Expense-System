<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Gastos\CategoriasGastos */

$this->title = 'Create Categoria';
$this->params['breadcrumbs'][] = ['label' => 'Categorias Gastos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="categorias-gastos-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
