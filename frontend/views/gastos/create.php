<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Gastos\Gastos */
/* @var $proyecto common\models\Proyecto\Proyecto|null */

$this->title = 'Insertar Gasto';
if (isset($proyecto) && $proyecto) {
    $this->params['breadcrumbs'][] = ['label' => $proyecto->nombre, 'url' => ['/dashboard/index', 'proyecto_id' => $proyecto->id]];
    $this->params['breadcrumbs'][] = ['label' => 'Gastos', 'url' => ['index', 'proyecto_id' => $proyecto->id]];
} else {
    $this->params['breadcrumbs'][] = ['label' => 'Gastos', 'url' => ['index']];
}
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gastos-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
