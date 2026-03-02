<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Proyecto\Proyecto */
/* @var $assignedUserIds int[] */
/* @var $assignedUsers array{id:int,username:string}[] */
/* @var $usersForSelect array id => username */

$this->title = 'Editar proyecto: ' . $model->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Proyectos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nombre, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Editar';
?>
<div class="proyecto-update">
    <?= $this->render('_form', [
        'model' => $model,
        'assignedUserIds' => $assignedUserIds ?? [],
        'assignedUsers' => $assignedUsers ?? [],
        'usersForSelect' => $usersForSelect ?? [],
    ]) ?>
</div>
