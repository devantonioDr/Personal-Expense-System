<?php

use frontend\models\VonageFileUploadForm;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\VonageConfig */
/* @var $form yii\widgets\ActiveForm */

$uploadForm = new VonageFileUploadForm();
?>

<div class="vonage-config-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">

        <?= $form->field($model, 'guId')->textInput(['maxlength' => true, 'readOnly' => true]) ?>
       
        <?= $form->field($model, 'test_phone')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'etiqueta')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'trunk_name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'vonage_app_id')->textInput(['maxlength' => true]) ?>

        <?= $form->field($uploadForm, 'private_key')->fileInput(['accept' => '.key']) ?>

        <?= $form->field($model, 'numbers')->textarea(['maxlength' => true, 'rows' => 10]) ?>

    </div>
    <div class="box-footer">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-primary btn-flat btn-block']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>