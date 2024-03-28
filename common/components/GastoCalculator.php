<?php

namespace common\components;

use frontend\models\Gastos;
use yii\base\Application;

class GastoCalculator
{
    protected $activeQuery;
    protected $application;

    public function __construct(\yii\db\ActiveQuery $activeQuery, Application $application)
    {
        $this->activeQuery = $activeQuery;
        $this->application = $application;
    }

    /**
     * Calcula el gasto total para el mes actual.
     *
     * @return float
     */
    public function calcularGastoMesActual()
    {
        $query = clone $this->activeQuery;

        $totalGasto = $query
            ->select(['SUM(monto)'])
            ->scalar();


        return $totalGasto ? (float) $totalGasto : 0.0;
    }

    /**
     * Calcula el gasto total para el día de hoy.
     *
     * @return float
     */
    public function calcularGastoDiaActual()
    {
        $currentTimestamp = time();
        $startOfDay = strtotime('midnight', $currentTimestamp);
        $endOfDay = strtotime('tomorrow', $startOfDay) - 1;

        $query = clone $this->activeQuery;

        $totalGasto = $query
            ->select(['SUM(monto)'])
            ->where(['>=', 'created_at', $startOfDay])
            ->andWhere(['<=', 'created_at', $endOfDay])
            ->andWhere(['user_id' => $this->application->user->id])
            ->scalar();


        return $totalGasto ? (float) $totalGasto : 0.0;
    }
}
