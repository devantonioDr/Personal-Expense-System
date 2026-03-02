<?php

use common\components\MyHelpers;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Gastos\GastosSearch */
/* @var $form yii\widgets\ActiveForm */
/* @var $categorias array id => nombre */
/* @var $proyecto_id int|null */

$categorias = $categorias ?? [];
$proyecto_id = $proyecto_id ?? null;
?>

<div class="row">
    <div class="col-md-6">
        <div class="box box-primary">
            <div class="box-body">
                <div class="gastos-search">

                    <?php $form = ActiveForm::begin([
                        'action' => array_merge(['index'], $proyecto_id ? ['proyecto_id' => $proyecto_id] : []),
                        'method' => 'get',
                    ]); ?>
                    <?php if ($proyecto_id): ?>
                        <?= Html::hiddenInput('proyecto_id', $proyecto_id) ?>
                    <?php endif; ?>


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





                    <div class="form-group">
                        <?php
                        echo $form->field($model, 'descripcion')->textarea();
                        ?>
                    </div>

                    <!-- Filtro de Categoría -->
                    <div class="form-group">
                        <?php
                        echo $form->field($model, 'categoria_id')->widget(Select2::classname(), [
                            'data' => $categorias,
                            'options' => ['placeholder' => 'Seleccionar categoría...'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);
                        ?>
                    </div>


                    <div class="form-group">
                        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
                        <?= Html::a('Reset', Url::to(array_merge(['gastos/index'], $proyecto_id ? ['proyecto_id' => $proyecto_id] : [])), ['class' => 'btn btn-default']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>

                </div>
            </div>
        </div>
    </div>
</div>