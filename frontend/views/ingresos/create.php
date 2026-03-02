<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Ingresos\Ingresos */
/* @var $proyecto common\models\Proyecto\Proyecto|null */

$this->title = 'Nuevo ingreso';
if (isset($proyecto) && $proyecto) {
    $this->params['breadcrumbs'][] = ['label' => $proyecto->nombre, 'url' => ['/dashboard/index', 'proyecto_id' => $proyecto->id]];
    $this->params['breadcrumbs'][] = ['label' => 'Ingresos', 'url' => ['index', 'proyecto_id' => $proyecto->id]];
} else {
    $this->params['breadcrumbs'][] = ['label' => 'Ingresos', 'url' => ['index']];
}
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ingresos-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
