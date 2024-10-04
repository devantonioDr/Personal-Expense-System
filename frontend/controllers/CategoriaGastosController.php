<?php

namespace frontend\controllers;

use Yii;
use common\models\Gastos\CategoriasGastos;
use common\models\Gastos\CategoriasGastosDashboardSearch;
use common\models\Gastos\CategoriasGastosSearch;
use common\models\Gastos\GastoCalculator;
use common\models\Gastos\GastosSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\VarDumper;

/**
 * CategoriaGastosController implements the CRUD actions for CategoriasGastos model.
 */
class CategoriaGastosController extends Controller
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
     * Lists all CategoriasGastos models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CategoriasGastosSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CategoriasGastos model.
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
     * Creates a new CategoriasGastos model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CategoriasGastos();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['update', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing CategoriasGastos model.
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
     * Deletes an existing CategoriasGastos model.
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
     * Lists all Gastos models.
     * @return mixed
     */
    public function actionDashboard()
    {
        $model = new CategoriasGastosDashboardSearch();
        $model->load(Yii::$app->request->queryParams);
        $model->setDefaultValues();

        // Filtrar por el usuario actual y el rango de fechas
        $dataProvider = $model->search(Yii::$app->user->id);

        // Obtener la data del DataProvider por adelantado para uso posterior
        $data = $dataProvider->getModels();

        // Check if it's an AJAX request
        if (Yii::$app->request->isAjax) {
            return $this->renderPartial('_dashboard/_grid', [
                'data' => $data,
                'dataProvider' => $dataProvider,
                'searchModel' => $model,
            ]);
        }

        return $this->render('_dashboard/index', [
            'data' => $data,
            'gastoCalculator' => new GastoCalculator($dataProvider->query, Yii::$app),
            'searchModel' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the CategoriasGastos model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return CategoriasGastos the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CategoriasGastos::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
