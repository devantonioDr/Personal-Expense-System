<?php

namespace common\services;

use common\models\Gastos\Gastos;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;


class GastoService
{
    public static function getTotalHoy(): float
    {
        $currentTimestamp = time();
        $startOfDay = strtotime('midnight', $currentTimestamp);
        $endOfDay = strtotime('tomorrow', $startOfDay) - 1;

        $totalGasto = Gastos::find()
            ->select(['SUM(monto)'])
            ->where(['>=', 'fecha_pago', date('Y-m-d', $startOfDay)])
            ->andWhere(['<=', 'fecha_pago', date('Y-m-d', $endOfDay)])
            // ->andWhere(['user_id' => $this->application->user->id])
            ->scalar();
        return $totalGasto ? (float) $totalGasto : 0.0;
    }

    public static function getTotalDelMes($year = null, $month = null): float
    {
        $year = $year ?? date('Y');
        $month = $month ?? date('m');

        // Inicio y fin del mes
        $startDate = "{$year}-{$month}-01";
        $endDate = date("Y-m-t", strtotime($startDate));

        return (float) Gastos::find()
            ->where(['between', 'fecha_pago', $startDate, $endDate])
            ->sum('monto');
    }


    public static function updateCategory($gastoId, $newCategoryId)
    {
        $gasto = Gastos::findOne($gastoId);
    
        if ($gasto === null) {
            throw new NotFoundHttpException('Gasto no encontrado.');
        }
    
        $gasto->categoria_id = $newCategoryId;
    
        if ($gasto->validate() && $gasto->save()) {
            return ['success' => true, 'message' => 'Categoría actualizada correctamente.'];
        }
    
        // Optionally log the error or inspect $gasto->getErrors()
        throw new ServerErrorHttpException('Error al actualizar la categoría.');
    }
}
