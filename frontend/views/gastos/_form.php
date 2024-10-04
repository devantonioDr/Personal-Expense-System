<?php

use common\models\Gastos\CategoriasGastos;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Gastos\Gastos */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="gastos-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">

        <?= $form->field($model, 'descripcion')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'monto')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'categoria_id')->dropDownList(
            CategoriasGastos::find()->select(['nombre', 'id'])->indexBy('id')->column(),
            ['prompt' => 'Seleccionar categoría']
        ) ?>

        <?= $form->field($model, 'fecha_pago')->input('date') ?> <!-- Campo para la fecha del gasto -->

    </div>
    <div class="box-footer">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>