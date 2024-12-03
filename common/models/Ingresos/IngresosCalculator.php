<?php

namespace common\models\Ingresos;

use yii\base\Application;

class IngresosCalculator
{
    protected $activeQuery;
    protected $application;

    public function __construct($startDate, $endDate, Application $application)
    {
        $this->activeQuery = $activeQuery;
        $this->application = $application;
    }

    /**
     * Calcula el Ingresos total para el mes actual.
     *
     * @return float
     */
    public function calcularIngresosMesActual()
    {
        $query = clone $this->activeQuery;

        $totalIngresos = $query
            ->select(['SUM(monto)'])
            ->scalar();


        return $totalIngresos ? (float) $totalIngresos : 0.0;
    }

    /**
     * Calcula el Ingresos total para el día de hoy.
     *
     * @return float
     */
    public function calcularIngresosDiaActual()
    {
        $currentTimestamp = time();
        $startOfDay = strtotime('midnight', $currentTimestamp);
        $endOfDay = strtotime('tomorrow', $startOfDay) - 1;

        $query = clone $this->activeQuery;

        $totalIngresos = $query
            ->select(['SUM(monto)'])
            ->where(['>=', 'fecha_pago', date('Y-m-d', $startOfDay)])
            ->andWhere(['<=', 'fecha_pago', date('Y-m-d', $endOfDay)])
            ->andWhere(['user_id' => $this->application->user->id])
            ->scalar();


        return $totalIngresos ? (float) $totalIngresos : 0.0;
    }
}
