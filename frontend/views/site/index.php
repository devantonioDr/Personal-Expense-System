<?php

/* @var $this yii\web\View */

$this->title = 'Panel de Control';
$this->params['breadcrumbs'][] = $this->title;


$today = date('d-m-Y');
$tomorrow = date('d-m-Y', strtotime('+1 day'));


?>

