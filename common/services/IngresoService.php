<?php

namespace common\services;

use common\models\Ingresos\Ingresos;
use yii\db\Expression;
use yii\helpers\VarDumper;

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

    // obtenerIngresosMensuales
    public function getMonthlyIncome($userId, $year)
    {
        $result = Ingresos::find()
        ->select([
            new Expression("MONTH(fecha_pago) AS mes"),
            new Expression("SUM(monto) AS total")
        ])
        ->andWhere(['YEAR(fecha_pago)' => $year])
        ->groupBy([new Expression("MONTH(fecha_pago)")])
        ->asArray()
        ->all();

        

        // Aseguramos que todos los meses estén presentes
        $ingresosMensuales = array_fill(1, 12, 0);
        foreach ($result as $row) {
            $mes = (int)$row['mes'];
            $ingresosMensuales[$mes] = (float)$row['total'];
        }

    return $ingresosMensuales;
}
}
