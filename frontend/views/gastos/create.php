<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\Gastos */

$this->title = 'Insertar Gasto';
$this->params['breadcrumbs'][] = ['label' => 'Gastos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gastos-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
