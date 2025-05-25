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

        $service = new GastoService();
        $datos = $service->getGastosGroupedByCategoriesAndMonths($userId, $year);

        $ingresoService = new IngresoService();
        $monthlyIncome = $ingresoService->getMonthlyIncome($userId, $year);

        return $this->render('index', [
            'datos' => $datos,
            'ingresosMensuales' => $monthlyIncome,	
        ]);
    }
}
