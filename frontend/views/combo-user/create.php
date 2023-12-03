<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\ComboUser */

$this->title = 'Insertar un combo list';
$this->params['breadcrumbs'][] = ['label' => 'Combo Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="combo-user-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
