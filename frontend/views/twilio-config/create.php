<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\TwilioConfig */

$this->title = 'Crear una configuración de Twilio';
$this->params['breadcrumbs'][] = ['label' => 'Twilio', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="twilio-config-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
