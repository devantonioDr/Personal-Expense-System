<?php

namespace frontend\controllers;

use common\components\GastoCalculator;
use Yii;
use frontend\models\Gastos;
use frontend\models\GastosSearch;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

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


        $query = $model->baseQuery();
        $query->andWhere(['user_id' => Yii::$app->user->id]);


        $year =  $model->year;
        $month = $model->month;

        $timestamp = mktime(0, 0, 0, $month, 1, $year);

        // Get the beginning of the month timestamp
        $startOfMonth = mktime(0, 0, 0, $month, 1, $year);


        // Get the end of the month timestamp
        $endOfMonth = mktime(23, 59, 59, $month, date('t', $timestamp), $year);


        $query->andWhere(['>=', 'created_at', $startOfMonth]) // Beginning of the month
            ->andWhere(['<', 'created_at', $endOfMonth]) // End of the month (plus one day)
            ->all();


        $gastoCalculator = new GastoCalculator($query, Yii::$app);

        $query->andFilterWhere(['like', 'descripcion', $model->descripcion]);

        return $this->render('index', [
            'gastoCalculator' => $gastoCalculator,
            'searchModel' => $model,
            'dataProvider' => new ActiveDataProvider([
                'query' => $query,
                'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
            ]),
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
            return $this->redirect(['view', 'id' => $model->id]);
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
            return $this->redirect(['view', 'id' => $model->id]);
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
