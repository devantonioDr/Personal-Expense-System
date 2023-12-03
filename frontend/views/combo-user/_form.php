<?php



use frontend\models\ComboUserUploadForm;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$comboUserUploadForm = new ComboUserUploadForm();

/* @var $this yii\web\View */
/* @var $model frontend\models\ComboUser */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="combo-user-form box box-primary">
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    <div class="box-body table-responsive">

        <?= $form->field($model, 'name')->textInput(['maxlength' => true,'required'=>true]) ?>

        <?php //echo Html::textarea('list',"",['required'=>true,'rows'=>20,'placeholder'=>'user:pass']) ?>
     
        <?= $form->field($comboUserUploadForm, 'comboTxt')->fileInput(['accept' => '.txt']) ?>

    </div>
    <div class="box-footer">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-primary btn-flat btn-block']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
