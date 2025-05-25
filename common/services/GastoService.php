<?php

namespace common\services;

use common\models\Gastos\Gastos;
use common\models\Gastos\CategoriasGastos;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use yii\db\Expression;
use yii\helpers\VarDumper;


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

    // obtenerGastosAgrupadosPorCategoriaYMes
     public function getGastosGroupedByCategoriesAndMonths($userId, $year)
     {
        $result = Gastos::find()
            ->select([
                'categoria_id',
                "MONTH(fecha_pago) AS mes",
                new Expression("SUM(monto) AS total")
            ])
            ->andWhere(['YEAR(fecha_pago)' => $year])
            ->groupBy(['categoria_id', new Expression("MONTH(fecha_pago)")])
            ->asArray()
            ->all();

        // Obtener nombres de categorías
        $categorias = CategoriasGastos::find()
            ->select(['nombre'])
            ->indexBy('id')
            ->column();

        // VarDumper::dump($categorias, 10, true);

        // Formato: [categoria][mes] => total
        $datos = [];
        foreach ($result as $row) {
            $catNombre = $categorias[$row['categoria_id']] ?? 'No categorizados';
            $datos[$catNombre][(int)$row['mes']] = (float)$row['total'];
        }
    
        return $datos;
    }

}
