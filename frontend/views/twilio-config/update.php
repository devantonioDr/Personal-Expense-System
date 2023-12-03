<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\TwilioConfig */

$this->title = 'Update Twilio Config: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Twilio Configs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="twilio-config-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
