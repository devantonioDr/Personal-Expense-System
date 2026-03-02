<?php
use yii\helpers\Html;

$this->title = isset($proyecto) && $proyecto ? 'Dashboard - ' . Html::encode($proyecto->nombre) : 'Dashboard de Gastos e Ingresos';
if (isset($proyecto) && $proyecto) {
    $this->params['breadcrumbs'][] = ['label' => $proyecto->nombre, 'url' => ['/dashboard/index', 'proyecto_id' => $proyecto->id]];
}
$this->params['breadcrumbs'][] = $this->title;

$year = $year ?? date('Y');
$currentYear = (int) date('Y');
$yearsRange = range($currentYear, $currentYear - 10);
$baseGastosUrl = ['gastos/index', 'GastosSearch' => ['year' => $year]];
if (!empty($proyectoId)) {
    $baseGastosUrl['proyecto_id'] = $proyectoId;
}

// Nombres de los meses
$meses = [
    1 => 'Ene', 2 => 'Feb', 3 => 'Mar', 4 => 'Abr', 5 => 'May', 6 => 'Jun',
    7 => 'Jul', 8 => 'Ago', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dic'
];

// Inicializar acumuladores
$totalesMensuales = array_fill(1, 12, 0);
$granTotalGasto = 0;
$granTotalIngreso = 0;


?>

<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Resumen Financiero por Categoría</h3>
        <div class="box-tools pull-right">
            <?= Html::beginForm(['/dashboard/index'], 'get', ['class' => 'form-inline', 'style' => 'display: inline-flex; align-items: center; gap: 8px;']) ?>
            <?php if (!empty($proyectoId)): ?>
                <?= Html::hiddenInput('proyecto_id', $proyectoId) ?>
            <?php endif; ?>
            <label for="dashboard-year" class="control-label" style="margin: 0;">Año:</label>
            <?= Html::dropDownList('year', $year, array_combine($yearsRange, $yearsRange), [
                'id' => 'dashboard-year',
                'class' => 'form-control input-sm',
                'style' => 'width: auto;',
                'onchange' => 'this.form.submit();',
            ]) ?>
            <?= Html::endForm() ?>
        </div>
    </div>
    <div class="box-body table-responsive no-padding">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Categoría</th>
                    <?php foreach ($meses as $nombre): ?>
                        <th><?= $nombre ?></th>
                    <?php endforeach; ?>
                    <th>Total en el año</th>
                </tr>
            </thead>
            <tbody>
                <!-- GASTOS POR CATEGORÍA -->
                <?php foreach ($gastoPorCategoria as $categoriaId => $gastosPorMes): ?>
                    <tr>
                        <td>
                            <?php 
                                $catDesc = $catDescs[$categoriaId] ?? 'No categorizados';
                                echo Html::encode($catDesc)
                            ?>
                        </td>
                        <?php
                        $totalCategoria = 0;
                        foreach (range(1, 12) as $mes) {
                            
                            $monto = $gastosPorMes[$mes] ?? 0;
                            $totalCategoria += $monto;
                            $totalesMensuales[$mes] += $monto;

                            $gastosLinkParams = $baseGastosUrl;
                            $gastosLinkParams['GastosSearch']['categoria_id'] = $categoriaId;
                            $gastosLinkParams['GastosSearch']['month'] = $mes;
                            $link = Html::a(
                                '$' . number_format($monto, 2),
                                $gastosLinkParams,
                                ['class' => 'btn btn-xs btn-default']
                            );
                            echo '<td>' . $link . '</td>';
                        }
                        $granTotalGasto += $totalCategoria;
                        ?>
                        <td><strong>$ <?= number_format($totalCategoria, 2) ?></strong></td>
                    </tr>
                <?php endforeach; ?>

                <!-- TOTALES DE GASTOS -->
                <tr style="background-color: #f0f0f0; font-weight: bold;">
                    <td>Total Gastos</td>
                    <?php foreach (range(1, 12) as $mes): ?>
                        <td>$ <?= number_format($totalesMensuales[$mes], 2) ?></td>
                    <?php endforeach; ?>
                    <td>$ <?= number_format($granTotalGasto, 2) ?></td>
                </tr>

                <!-- TOTALES DE INGRESOS -->
                <tr style="background-color: #d0f0d0; font-weight: bold;">
                    <td>Total Ingresos</td>
                    <?php foreach (range(1, 12) as $mes): ?>
                        <?php
                        $ing = $ingresosMensuales[$mes] ?? 0;
                        $granTotalIngreso += $ing;
                        ?>
                        <td>$ <?= number_format($ing, 2) ?></td>
                    <?php endforeach; ?>
                    <td>$ <?= number_format($granTotalIngreso, 2) ?></td>
                </tr>

                <!-- RENDIMIENTO MENSUAL -->
                <tr style="background-color: #fce4d6; font-weight: bold;">
                    <td>Rendimiento</td>
                    <?php foreach (range(1, 12) as $mes): ?>
                        <?php
                        $diff = ($ingresosMensuales[$mes] ?? 0) - ($totalesMensuales[$mes] ?? 0);
                        $color = $diff < 0 ? 'red' : 'green';
                        ?>
                        <td><span style="color:<?= $color ?>;">$ <?= number_format($diff, 2) ?></span></td>
                    <?php endforeach; ?>
                    <td>
                        <?php
                        $totalDiff = $granTotalIngreso - $granTotalGasto;
                        $color = $totalDiff < 0 ? 'red' : 'green';
                        ?>
                        <span style="color:<?= $color ?>;">$ <?= number_format($totalDiff, 2) ?></span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
