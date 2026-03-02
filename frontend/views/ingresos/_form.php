<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Ingresos\Ingresos */
/* @var $form yii\widgets\ActiveForm */
$this->registerCssFile(\Yii::getAlias('@web/css/adjunto-foto.css'), ['depends' => [\yii\web\YiiAsset::class]]);
?>

<div class="ingresos-form box box-primary">
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    <div class="box-body table-responsive">

        <?= $form->field($model, 'descripcion')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'monto')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'fecha_pago')->input('date') ?>

        <div class="form-group field-ingresos-adjuntofile">
            <label class="control-label"><?= $model->getAttributeLabel('adjuntoFile') ?></label>
            <div class="adjunto-foto-wrap">
                <?= $form->field($model, 'adjuntoFile')->fileInput([
                    'id' => 'ingresos-adjuntofile',
                    'accept' => 'image/*',
                    'class' => 'adjunto-foto-input',
                    'capture' => 'environment',
                ])->label(false) ?>
                <label class="adjunto-foto-zone" for="ingresos-adjuntofile">
                    <span class="adjunto-foto-icon">📷</span>
                    <span class="adjunto-foto-text">Arrastra una foto aquí o haz clic para elegir</span>
                    <span class="adjunto-foto-hint">PNG, JPG, GIF o WebP · Máx. 5 MB</span>
                    <span class="adjunto-foto-hint adjunto-foto-mobile-hint">En el celular puedes tomar una foto directamente</span>
                </label>
                <?php if ($model->adjunto && $model->getAdjuntoUrl()): ?>
                <div class="adjunto-foto-preview adjunto-foto-preview-existing" id="adjunto-preview-existing-ingresos">
                    <img src="<?= Html::encode($model->getAdjuntoUrl()) ?>" alt="Comprobante" class="adjunto-thumb" />
                    <span class="adjunto-foto-current">Foto actual (se reemplazará si subes otra)</span>
                </div>
                <?php endif; ?>
                <div class="adjunto-foto-preview adjunto-foto-preview-new" id="adjunto-preview-new-ingresos" style="display:none;">
                    <img id="adjunto-preview-img-ingresos" alt="Vista previa" class="adjunto-thumb" />
                    <span class="adjunto-foto-current">Vista previa — se subirá al guardar</span>
                </div>
            </div>
            <div class="help-block"></div>
        </div>

    </div>
    <div class="box-footer">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<?php
$js = <<<'JS'
(function(){
    var input = document.getElementById('ingresos-adjuntofile');
    var previewNew = document.getElementById('adjunto-preview-new-ingresos');
    var previewImg = document.getElementById('adjunto-preview-img-ingresos');
    var previewExisting = document.getElementById('adjunto-preview-existing-ingresos');
    if (!input) return;
    input.addEventListener('change', function(){
        var file = this.files && this.files[0];
        if (file && file.type.indexOf('image/') === 0) {
            var r = new FileReader();
            r.onload = function(e){
                previewImg.src = e.target.result;
                previewNew.style.display = 'block';
                if (previewExisting) previewExisting.style.display = 'none';
            };
            r.readAsDataURL(file);
        } else {
            previewNew.style.display = 'none';
            if (previewImg) previewImg.removeAttribute('src');
            if (previewExisting) previewExisting.style.display = 'block';
        }
    });
})();
JS;
$this->registerJs($js);