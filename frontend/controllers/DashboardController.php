<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use common\services\GastoService;
use common\services\IngresoService;

class DashboardController extends Controller
{
    public function actionIndex()
    {
        $userId = Yii::$app->user->id;
        $year = 2025;

        $gastoService = new GastoService();
        $gastoPorCategoria = $gastoService->getGastosGroupedByCategoriesAndMonths($userId, $year);

        $ingresoService = new IngresoService();
        $monthlyIncome = $ingresoService->getMonthlyIncome($userId, $year);

        $catDescs = GastoService::obtenerCategorias();

        return $this->render('index', [
            'gastoPorCategoria' => $gastoPorCategoria,
            'ingresosMensuales' => $monthlyIncome,
            'catDescs' => $catDescs,	
        ]);
    }
}
