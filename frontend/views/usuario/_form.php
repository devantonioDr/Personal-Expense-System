<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="usuario-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">
        <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>
        <?php if (!$model->isNewRecord): ?>
            <p class="help-block">Dejar en blanco para mantener la contraseña actual.</p>
        <?php endif; ?>
        <?= $form->field($model, 'user_rol')->dropDownList([
            \common\models\User::ROLE_OPERADOR => 'Operador',
            \common\models\User::ROLE_ADMINISTRADOR => 'Administrador',
        ]) ?>
        <?= $form->field($model, 'status')->dropDownList([
            \common\models\User::STATUS_ACTIVE => 'Activo',
            \common\models\User::STATUS_INACTIVE => 'Inactivo',
        ]) ?>
    </div>
    <div class="box-footer">
        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Guardar', ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('Cancelar', $model->isNewRecord ? ['index'] : ['view', 'id' => $model->id], ['class' => 'btn btn-default btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
