<?php
use yii\helpers\Html;

$this->title = 'Dashboard de Gastos e Ingresos';
$this->params['breadcrumbs'][] = $this->title;

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
        <h3 class="box-title">Resumen Financiero por Categoría - Año <?= date('Y') ?></h3>
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
                <?php foreach ($datos as $categoria => $gastos): ?>
                    <tr>
                        <td><?= Html::encode($categoria) ?></td>
                        <?php
                        $totalCategoria = 0;
                        foreach (range(1, 12) as $mes) {
                            $monto = $gastos[$mes] ?? 0;
                            $totalCategoria += $monto;
                            $totalesMensuales[$mes] += $monto;
                            echo '<td>$ ' . number_format($monto, 2) . '</td>';
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
