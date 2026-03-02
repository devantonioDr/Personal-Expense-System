<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Proyecto\Proyecto */

$this->title = 'Nuevo proyecto';
$this->params['breadcrumbs'][] = ['label' => 'Proyectos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="proyecto-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
