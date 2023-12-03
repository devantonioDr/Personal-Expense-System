<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\TwilioConfig */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="twilio-config-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">

        <?= $form->field($model, 'guId')->textInput(['maxlength' => true,'readOnly'=>true]) ?>

        <?= $form->field($model, 'test_phone')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'etiqueta')->textInput(['maxlength' => true]) ?>

        
        <?= $form->field($model, 'trunk_name')->textInput(['maxlength' => true]) ?>
        

        <?= $form->field($model, 'account_sid')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'auth_token')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'numbers')->textarea(['rows' => 6]) ?>

    </div>
    <div class="box-footer">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
