<?php

namespace common\services;

use common\models\Ingresos\Ingresos;

class IngresoService
{
    public static function getTotalDelMes($year = null, $month = null): float
    {
        $year = $year ?? date('Y');
        $month = $month ?? date('m');

        // Inicio y fin del mes
        $startDate = "{$year}-{$month}-01";
        $endDate = date("Y-m-t", strtotime($startDate));

        return (float) Ingresos::find()
            ->where(['between', 'fecha_pago', $startDate, $endDate])
            ->sum('monto');
    }
}
