<?php

namespace frontend\controllers;

use Yii;
use common\models\Gastos\Gastos;
use common\models\Gastos\GastosSearch;
use common\models\Gastos\GastoCalculator;
use common\models\Ingresos\Ingresos;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * GastosController implements the CRUD actions for Gastos model.
 */
class GastosController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Gastos models.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = new GastosSearch();
        $model->load(Yii::$app->request->queryParams);
        $model->setDefaultValues();

        // Filtrar por el usuario actual y el rango de fechas
        $dataProvider = $model->search(Yii::$app->user->id);

        // Check if it's an AJAX request
        if (Yii::$app->request->isAjax) {
            return $this->renderPartial('_grid', [
                'dataProvider' => $dataProvider,
                'searchModel' => $model,
            ]);
        }

        // Calcular el total de gastos
        $gastoCalculator = new GastoCalculator($dataProvider->query, Yii::$app);

        // Obtener el ingreso total del mes actual
        $year = Yii::$app->request->get('GastosSearch')['year'] ?? date('Y');
        $month = Yii::$app->request->get('GastosSearch')['month'] ?? date('m');

        // Construct the start and end dates for the selected month
        $currentMonthStart = "{$year}-{$month}-01";
        $currentMonthEnd = date("Y-m-t", strtotime($currentMonthStart));

        $totalIngresoMes = Ingresos::find()
            ->where(['between', 'fecha_pago', $currentMonthStart, $currentMonthEnd])
            ->sum('monto');



        return $this->render('index', [
            'gastoCalculator' => $gastoCalculator,
            'searchModel' => $model,
            'dataProvider' => $dataProvider,
            'totalIngresoMes' => $totalIngresoMes,
        ]);
    }

    /**
     * Displays a single Gastos model.
     * @param int $id ID
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Gastos model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Gastos();

        $model->user_id = Yii::$app->user->id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['update', 'id' => $model->id]);
        } else {
            // var_dump($model->errors);
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Gastos model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['update', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Gastos model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionUpdateCategory()
    {
        Yii::$app->response->format = Response::FORMAT_JSON; // Para responder en formato JSON

        if (Yii::$app->request->isAjax) {
            $id = Yii::$app->request->post('id'); // ID del gasto
            $categoriaId = Yii::$app->request->post('categoria_id'); // ID de la categoría

            $gasto = Gastos::findOne($id);
            if ($gasto !== null) {
                $gasto->categoria_id = $categoriaId; // Actualiza la categoría

                if ($gasto->save()) {
                    return ['success' => true, 'message' => 'Categoría actualizada correctamente.'];
                } else {
                    return ['success' => false, 'message' => 'Error al actualizar la categoría.'];
                }
            } else {
                return ['success' => false, 'message' => 'Gasto no encontrado.'];
            }
        }

        throw new NotFoundHttpException('La solicitud no es válida.');
    }

    /**
     * Finds the Gastos model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Gastos the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Gastos::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
