<?php

namespace common\components;

use frontend\models\Gastos;
use yii\base\Application;

class GastoCalculator
{
    protected $gastoModel;
    protected $application;

    public function __construct(Gastos $gastoModel, Application $application)
    {
        $this->gastoModel = $gastoModel;
        $this->application = $application;
    }

    /**
     * Calcula el gasto total para el mes actual.
     *
     * @return float
     */
    public function calcularGastoMesActual()
    {
        $mesActual = date('Y-m-01');
        $hoy = date('Y-m-d');

        $totalGasto = $this->gastoModel->find()
            ->select(['SUM(monto)'])
            ->where(['>=', 'FROM_UNIXTIME(created_at)', $mesActual . ' 00:00:00'])
            ->andWhere(['<=', 'FROM_UNIXTIME(created_at)', $hoy . ' 23:59:59'])
            ->andWhere(['user_id' => $this->application->user->id])
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
        $hoy = date('Y-m-d');

        $totalGasto = $this->gastoModel->find()
            ->select(['SUM(monto)'])
            ->where(['>=', 'FROM_UNIXTIME(created_at)', $hoy . ' 00:00:00'])
            ->andWhere(['<=', 'FROM_UNIXTIME(created_at)', $hoy . ' 23:59:59'])
            ->andWhere(['user_id' => $this->application->user->id])
            ->scalar();


        return $totalGasto ? (float) $totalGasto : 0.0;
    }
}
