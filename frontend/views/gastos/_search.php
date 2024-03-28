<?php

use common\components\MyHelpers;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model frontend\models\GastosSearch */
/* @var $form yii\widgets\ActiveForm */

?>


<div class="box box-primary">
    <div class="box-body">
        <div class="gastos-search">

            <?php $form = ActiveForm::begin([
                'action' => ['index'],
                'method' => 'get',
            ]); ?>

            <div class="form-group">
                <?php
                echo $form->field($model, 'year')->dropDownList(
                    MyHelpers::makeYearRange(),
                    ['prompt' => 'Select Year']
                );
                ?>
            </div>

            <div class="form-group">
                <?php
                echo $form->field($model, 'month')->dropDownList(
                    MyHelpers::makeMonths(),
                    ['prompt' => 'Select Month']
                );
                ?>
            </div>

            <div class="form-group">
                <?php
                echo $form->field($model, 'descripcion')->textarea();
                ?>
            </div>


            <div class="form-group">
                <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Reset', Url::to([""]), ['class' => 'btn btn-default']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>