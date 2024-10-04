<?php

use yii\helpers\Json;



// Data for the pie chart (browser usage)
$pieData = [
    ['value' => 700, 'color' => '#f56954', 'highlight' => '#f56954', 'label' => 'Chrome'],
    ['value' => 500, 'color' => '#00a65a', 'highlight' => '#00a65a', 'label' => 'IE'],
    ['value' => 400, 'color' => '#f39c12', 'highlight' => '#f39c12', 'label' => 'FireFox'],
    ['value' => 600, 'color' => '#00c0ef', 'highlight' => '#00c0ef', 'label' => 'Safari'],
    ['value' => 300, 'color' => '#3c8dbc', 'highlight' => '#3c8dbc', 'label' => 'Opera'],
    ['value' => 100, 'color' => '#d2d6de', 'highlight' => '#d2d6de', 'label' => 'Navigator'],
];

$pieData = array_map(function ($data) {
    return [
        'value' => $data->total_gastos,
        'color' => $data->color_etiqueta,
        'highlight' => $data->color_etiqueta,
        'label' => $data->nombre,
    ];
}, $data);

// Convert PHP arrays to JSON format
$pieDataJson = Json::encode($pieData);

// JavaScript to render the pie chart and legend
$js = <<<JS
    $(function () {
        var pieChartCanvas = $('#pieChart').get(0).getContext('2d');

        // Parse the JSON data passed from PHP
        var PieData = $pieDataJson;

        var pieOptions = {
            segmentShowStroke: true,
            segmentStrokeColor: '#fff',
            segmentStrokeWidth: 1,
            percentageInnerCutout: 50, // Doughnut chart
            animationSteps: 100,
            animationEasing: 'easeOutBounce',
            animateRotate: true,
            animateScale: false,
            responsive: true,
            maintainAspectRatio: false,
            tooltipTemplate: '<%=value %> <%=label%> users'
        };

        // Create the doughnut chart
        var pieChart = new Chart(pieChartCanvas);
        pieChart.Doughnut(PieData, pieOptions);

        // Dynamically generate the legend
        var legendHtml = '';
        for (var i = 0; i < PieData.length; i++) {
            legendHtml += '<li><span style="background-color:' + PieData[i].color + '"></span>' + PieData[i].label + '</li>';
        }
        $('#chartLegend').html(legendHtml);
    });
JS;

// Register the JavaScript
$this->registerJs($js, \yii\web\View::POS_READY);
?>


<!-- Canvas for Pie Chart -->
<div class="chart-responsive">
    <canvas id="pieChart" style="height: 250px; width: 250px;"></canvas>
</div>


<!-- Dynamic Legend -->
<ul id="chartLegend" class="chart-legend"></ul>



<style>
    .chart-legend {
        list-style: none;
        padding: 0;
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
    }

    .chart-legend li {
        display: flex;
        align-items: center;
        margin-right: 15px;
    }

    .chart-legend li span {
        display: inline-block;
        width: 20px;
        height: 20px;
        margin-right: 5px;
    }
</style>