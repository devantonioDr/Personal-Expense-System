<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\VonageConfig */

$this->title = 'Crear una configuracion de vonage';
$this->params['breadcrumbs'][] = ['label' => 'Vonage Configs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="vonage-config-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
