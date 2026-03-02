<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use common\services\GastoService;
use common\services\IngresoService;
use common\services\ProyectoService;

class DashboardController extends Controller
{
    public function actionIndex()
    {
        $year = (int) (Yii::$app->request->get('year') ?? date('Y'));
        $proyectoId = Yii::$app->request->get('proyecto_id');
        $proyecto = null;
        if ($proyectoId !== null && $proyectoId !== '') {
            $proyectoId = (int) $proyectoId;
            $proyecto = ProyectoService::getProyectoParaUsuario($proyectoId);
            if (!$proyecto) {
                $proyectoId = null;
            }
        }

        $gastoService = new GastoService();
        $gastoPorCategoria = $gastoService->getGastosGroupedByCategoriesAndMonths($year, $proyectoId);

        $monthlyIncome = IngresoService::getMonthlyIncome($year, $proyectoId);

        $catDescs = GastoService::obtenerCategorias();

        return $this->render('index', [
            'gastoPorCategoria' => $gastoPorCategoria,
            'ingresosMensuales' => $monthlyIncome,
            'catDescs' => $catDescs,
            'proyectoId' => $proyectoId,
            'proyecto' => $proyecto,
            'year' => $year,
        ]);
    }
}
