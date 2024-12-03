<?php

use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\Gastos\GastosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $gastoCalculator common\models\Gasto\GastoCalculatorGastoCalculator */

$this->title = 'Gastos';
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
    .kv-plugin-loading {
        display: none;
    }
</style>

<?php echo $this->render('_top_header', [
    'hoy' => $gastoCalculator->calcularGastoDiaActual(),
    'mesActual' => $gastoCalculator->calcularGastoMesActual()
]); ?>

<div class="row">
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-green">
            <div class="inner">
                <h3>
                    <?= Yii::$app->formatter->asCurrency($totalIngresoMes ?? 0, 'USD') ?>
                </h3>
                <p>Ingreso del Mes Actual</p>
            </div>
            <div class="icon">
                <i class="fa fa-money"></i>
            </div>
        </div>
    </div>
</div>



<?php echo $this->render('_search', ['model' => $searchModel]); ?>

<div class="gastos-index box box-primary">
    <div class="box-header with-border">
        <?= Html::a('Registrar Gasto', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <div class="box-body table-responsive no-padding">
        <?php Pjax::begin(); ?> <!-- Begin Pjax Container -->
        <?php echo $this->render(
            "_grid",
            [
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel
            ]
        ) ?>
        <?php Pjax::end(); ?> <!-- End Pjax Container -->
    </div>
</div>



<?php
$script = <<< JS
$(document).on('pjax:end', function() {
    // Re-inicializar Select2 después de que Pjax complete la actualización
    $('.categoria-select').select2({
        placeholder: 'Seleccionar categoría'
    });
    
    // Si hay algún plugin de Kartik-V que esté en estado "loading", reinícialo.
    $('[data-krajee-select2]').each(function() {
        var id = $(this).attr('id');
        if ($('#' + id).data('select2')) {
            $('#' + id).select2('destroy').select2(); // Destruir e inicializar de nuevo
        }
    });

    // Manejar el cambio de categoría con AJAX
    $(document).on('change', '.categoria-select', function() {
        var categoriaId = $(this).val(); // Obtiene el ID de la categoría seleccionada
        var gastoId = $(this).data('id'); // Obtiene el ID del gasto

        $.ajax({
            url: '/gastos/update-category', // Cambia esto a la URL correcta
            type: 'POST',
            data: {
                id: gastoId,
                categoria_id: categoriaId,
            },
            success: function(response) {
                console.log('Categoría actualizada con éxito:', response);
            },
            error: function() {
                alert('Hubo un error al actualizar la categoría.');
            }
        });
    });
});

// Inicializa Select2 la primera vez que se carga la página
$('.categoria-select').select2({
    placeholder: 'Seleccionar categoría'
});
JS;

$this->registerJs($script);
?>