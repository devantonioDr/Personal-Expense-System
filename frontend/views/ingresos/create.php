<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Ingresos\Ingresos */

$this->title = 'Create Ingreso';
$this->params['breadcrumbs'][] = ['label' => 'Ingresos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ingresos-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
