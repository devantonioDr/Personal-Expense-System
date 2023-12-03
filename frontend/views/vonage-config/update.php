<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model frontend\models\VonageConfig */

$this->title = 'Actualizar Vonage Config: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Vonage Configs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="vonage-config-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    <div class="vonage-config-form box box-primary">

        <div class="box-body table-responsive">


            <?= HTML::a('Private key link',Url::to(["./uploads/".$model->private_key]),['target'=>'_blank'])  ?>

        </div>
    </div>
</div>