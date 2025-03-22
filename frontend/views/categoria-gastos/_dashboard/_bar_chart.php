<?php

use yii\helpers\Json;
use yii\helpers\VarDumper;

/**
 * Helper to convert HEX to RGBA
 */
function hexToRgba($hex, $alpha = 0.8)
{
    $hex = str_replace('#', '', $hex);
    if (strlen($hex) == 3) {
        $r = hexdec(str_repeat($hex[0], 2));
        $g = hexdec(str_repeat($hex[1], 2));
        $b = hexdec(str_repeat($hex[2], 2));
    } else {
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
    }
    return "rgba($r,$g,$b,$alpha)";
}

// Prepare labels and data
$barLabels = array_map(fn($d) => $d->nombre, $data);
$barData = array_map(fn($d) => $d->total_gastos, $data);
$barColors = array_map(fn($d) => hexToRgba($d->color_etiqueta, 0.8), $data);
$barBorderColors = array_map(fn($d) => hexToRgba($d->color_etiqueta, 1), $data);

// Build the dataset with per-bar colors
$barChartData = [
    'labels' => $barLabels,
    'datasets' => [[
        'label' => 'Gastos',
        'fillColor' => $barColors,
        'strokeColor' => $barBorderColors,
        'highlightFill' => $barColors,
        'highlightStroke' => $barBorderColors,
        'data' => $barData,
    ]]
];

$barChartJson = Json::encode($barChartData);

$js = <<<JS
    $(function () {
        var barChartCanvas = $('#barChart').get(0).getContext('2d');
        var barChartData = $barChartJson;

         // Utility to add comma separators and $ symbol
         function formatCurrency(value) {
            return '$' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        }

        // Monkey patch the template rendering for tooltips
        Chart.defaults.global.tooltipTemplate = function (label) {
            var value = label.value;
            var name = label.label;
            return name + ': ' + formatCurrency(value);
        };

        var barOptions = {
            scaleFontSize: window.innerWidth < 500 ? 10 : 12,
            responsive: true,
            maintainAspectRatio: false,

            scaleBeginAtZero: true,
            scaleShowGridLines: true,
            scaleGridLineColor: 'rgba(0,0,0,.05)',
            scaleGridLineWidth: 1,
            barShowStroke: true,
            barStrokeWidth: 2,
            barValueSpacing: 5,
            barDatasetSpacing: 1,
            responsive: true,
            maintainAspectRatio: false,
            // tooltipTemplate: "<%= value %> - <%= label %>"
        };

        var barChart = new Chart(barChartCanvas).Bar(barChartData, barOptions);
    });
JS;

$this->registerJs($js, \yii\web\View::POS_READY);
?>

<!-- Canvas for Bar Chart -->
<div class="chart-responsive" style="overflow-x: auto;height: 300px;">
    <canvas id="barChart" style=" min-width: 500px;"></canvas>
</div>


<style>
    .chart-container {
        position: relative;
        width: 100%;
        max-width: 100%;
        height: auto;
        padding-bottom: 56.25%;
        /* 16:9 aspect ratio for responsiveness */
    }

    .chart-container canvas {
        position: absolute;
        top: 0;
        left: 0;
        width: 100% !important;
        height: 100% !important;
    }
</style>