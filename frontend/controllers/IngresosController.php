<?php

namespace frontend\controllers;

use Yii;
use common\models\Ingresos\Ingresos;
use common\models\Ingresos\IngresosSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * IngresosController implements the CRUD actions for Ingresos model.
 */
class IngresosController extends Controller
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
     * Lists all Ingresos models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new IngresosSearch();
        $searchModel->load(Yii::$app->request->queryParams);
        $searchModel->setDefaultValues();

        // Filtrar por el usuario actual y el rango de fechas
        $dataProvider = $searchModel->search(Yii::$app->user->id);

    
        // Obtener el ingreso total del mes actual
        $year = Yii::$app->request->get('IngresosSearch')['year'] ?? date('Y');
        $month = Yii::$app->request->get('IngresosSearch')['month'] ?? date('m');

        // Construct the start and end dates for the selected month
        $currentMonthStart = "{$year}-{$month}-01";
        $currentMonthEnd = date("Y-m-t", strtotime($currentMonthStart));

        $totalIngresoMes = Ingresos::find()
            ->where(['between', 'fecha_pago', $currentMonthStart, $currentMonthEnd])
            ->sum('monto');

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'totalIngresoMes' => $totalIngresoMes,
        ]);
    }

    /**
     * Displays a single Ingresos model.
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
     * Creates a new Ingresos model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Ingresos();

        $model->user_id = Yii::$app->user->id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Ingresos model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Ingresos model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Ingresos model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Ingresos the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Ingresos::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
