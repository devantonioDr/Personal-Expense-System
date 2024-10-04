<?php

use common\components\MyHelpers;
use common\models\Gastos\CategoriasGastos;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model common\models\Gastos\GastosSearch */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="row">
    <div class="col-md-6">
        <div class="box box-primary">
            <div class="box-body">
                <div class="gastos-search">

                    <?php $form = ActiveForm::begin([
                        'action' => ['dashboard'],
                        'method' => 'get',
                    ]); ?>


                    <div class="row">
                        <div class="col-xs-6">
                            <div class="form-group">
                                <?php
                                echo $form->field($model, 'year')->dropDownList(
                                    MyHelpers::makeYearRange(),
                                    ['prompt' => 'Select Year']
                                );
                                ?>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="form-group">
                                <?php
                                echo $form->field($model, 'month')->dropDownList(
                                    MyHelpers::makeMonths(),
                                    ['prompt' => 'Select Month']
                                );
                                ?>
                            </div>
                        </div>
                    </div>


                    <!-- Filtro de Categoría -->
                    <div class="form-group">
                        <?php
                        // Obtener todas las categorías para el select
                        $categorias = CategoriasGastos::find()->all();

                        // Utilizar Select2 para el filtro de categoría
                        echo $form->field($model, 'id')->widget(Select2::classname(), [
                            'data' => \yii\helpers\ArrayHelper::map($categorias, 'id', 'nombre'),
                            'options' => ['placeholder' => 'Seleccionar categoría...'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);
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
    </div>
</div>