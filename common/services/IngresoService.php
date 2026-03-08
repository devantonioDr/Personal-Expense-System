<?php

namespace common\services;

use common\models\Ingresos\Ingresos;
use Yii;
use yii\db\Expression;
use yii\helpers\VarDumper;

class IngresoService
{
    /**
     * Total de ingresos del mes para el usuario actual (y opcionalmente un proyecto).
     * @param int|null $year
     * @param int|null $month
     * @param int|null $proyectoId
     * @param int|null $userId Si null, usa el usuario logueado.
     * @return float
     */
    public static function getTotalDelMes($year = null, $month = null, ?int $proyectoId = null, ?int $userId = null): float
    {
        // $userId = $userId ?? (Yii::$app->user->isGuest ? null : (int) Yii::$app->user->id);
        $year = $year ?? date('Y');
        $month = $month ?? date('m');

        $startDate = "{$year}-{$month}-01";
        $endDate = date("Y-m-t", strtotime($startDate));

        $query = Ingresos::find()
            ->where(['between', 'fecha_pago', $startDate, $endDate])
            ->andWhere(['deleted' => 0]);
        if ($userId !== null) {
            $query->andWhere(['user_id' => $userId]);
        }
        if ($proyectoId !== null) {
            $query->andWhere(['proyecto_id' => $proyectoId]);
        }
        return (float) $query->sum('monto');
    }

    /**
     * Ingresos mensuales por mes del año para el usuario actual (y opcionalmente un proyecto).
     * @param int $year
     * @param int|null $proyectoId
     * @param int|null $userId Si null, usa el usuario logueado.
     * @return array
     */
    public static function getMonthlyIncome($year, ?int $proyectoId = null, ?int $userId = null): array
    {
        // $userId = $userId ?? (Yii::$app->user->isGuest ? null : (int) Yii::$app->user->id);
        $query = Ingresos::find()
            ->select([
                new Expression("MONTH(fecha_pago) AS mes"),
                new Expression("SUM(monto) AS total")
            ])
            ->andWhere(['YEAR(fecha_pago)' => $year])
            ->andWhere(['deleted' => 0]);
        if ($userId !== null) {
            $query->andWhere(['user_id' => $userId]);
        }
        if ($proyectoId !== null) {
            $query->andWhere(['proyecto_id' => $proyectoId]);
        }
        $result = $query->groupBy([new Expression("MONTH(fecha_pago)")])
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
