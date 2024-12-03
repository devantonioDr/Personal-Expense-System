<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Ingresos\Ingresos */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ingresos-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">

        <?= $form->field($model, 'descripcion')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'monto')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'fecha_pago')->input('date') ?> <!-- Campo para la fecha del gasto -->


    </div>
    <div class="box-footer">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>