<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model common\models\Proyecto\Proyecto */
/* @var $form yii\widgets\ActiveForm */
/* @var $assignedUserIds int[] */
/* @var $assignedUsers array{id:int,username:string}[] */
/* @var $usersForSelect array id => username */
?>
<div class="proyecto-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">
        <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>

        <?php if (isset($assignedUserIds) && isset($usersForSelect)): ?>
        <div class="form-group">
            <label class="control-label">Usuarios con permiso</label>
            <style>.proyecto-usuarios-chips .label { display: inline-block; margin: 2px 4px 2px 0; }
            .proyecto-usuarios-chips .proyecto-chip-remove { margin-left: 4px; color: inherit; opacity: 0.8; }
            .proyecto-usuarios-chips .proyecto-chip-remove:hover { opacity: 1; }</style>
            <p class="help-block">Usuarios que pueden ver y usar este proyecto. El dueño del proyecto siempre tiene acceso.</p>
            <div id="proyecto-usuarios-chips" class="proyecto-usuarios-chips margin-bottom">
                <?php foreach ($assignedUsers as $u): ?>
                <span class="label label-primary proyecto-chip" data-user-id="<?= (int) $u['id'] ?>">
                    <?= Html::encode($u['username']) ?>
                    <a href="#" class="proyecto-chip-remove" aria-label="Quitar">&times;</a>
                </span>
                <?php endforeach; ?>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <?= Select2::widget([
                        'name' => 'user_add_select',
                        'id' => 'proyecto-user-add',
                        'value' => '',
                        'data' => $usersForSelect,
                        'options' => ['placeholder' => 'Agregar usuario...'],
                        'pluginOptions' => ['allowClear' => true],
                    ]) ?>
                </div>
            </div>
            <select name="user_ids[]" id="proyecto-user-ids" multiple="multiple" style="display:none;">
                <?php foreach ($assignedUserIds as $uid): ?>
                <option value="<?= (int) $uid ?>" selected="selected"></option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php endif; ?>
    </div>
    <div class="box-footer">
        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Guardar', ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('Cancelar', ['index'], ['class' => 'btn btn-default btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<?php if (isset($assignedUserIds) && isset($usersForSelect)): ?>
<?php
$usersForSelectJson = json_encode($usersForSelect ?: []);
$this->registerJs(<<<JS
(function() {
    var \$chips = $('#proyecto-usuarios-chips');
    var \$select = $('#proyecto-user-ids');
    var \$add = $('#proyecto-user-add');
    var usersData = $usersForSelectJson;

    function syncSelectFromChips() {
        var ids = [];
        \$chips.find('.proyecto-chip').each(function() {
            ids.push(parseInt($(this).data('user-id'), 10));
        });
        \$select.find('option').remove();
        ids.forEach(function(id) {
            \$select.append($('<option></option>').val(id).prop('selected', true));
        });
    }

    \$chips.on('click', '.proyecto-chip-remove', function(e) {
        e.preventDefault();
        $(this).closest('.proyecto-chip').remove();
        syncSelectFromChips();
    });

    \$add.on('select2:select', function(e) {
        var id = e.params.data.id;
        var text = e.params.data.text;
        if (\$chips.find('.proyecto-chip[data-user-id="' + id + '"]').length) return;
        var \$chip = $('<span class="label label-primary proyecto-chip" data-user-id="' + id + '">' +
            escapeHtml(text) + ' <a href="#" class="proyecto-chip-remove" aria-label="Quitar">&times;</a></span> ');
        \$chips.append(\$chip);
        \$select.append($('<option></option>').val(id).prop('selected', true));
        \$add.val(null).trigger('change');
    });

    function escapeHtml(t) {
        var div = document.createElement('div');
        div.textContent = t;
        return div.innerHTML;
    }
})();
JS
);
?>
<?php endif; ?>
