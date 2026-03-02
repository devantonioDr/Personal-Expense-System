<?php

namespace frontend\controllers;

use Yii;
use common\models\Gastos\GastosCategoria;
use common\models\Gastos\GastosCategoriaDashboardSearch;
use common\models\Gastos\GastosCategoriaSearch;
use common\services\GastoService;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CategoriaGastosController implementa las acciones CRUD para el modelo GastosCategoria.
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
     * Lista todos los modelos GastosCategoria.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new GastosCategoriaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Muestra un modelo GastosCategoria.
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
     * Crea un nuevo GastosCategoria.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new GastosCategoria();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['update', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Actualiza un GastosCategoria existente.
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
     * Elimina un GastosCategoria existente.
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
        $model = new GastosCategoriaDashboardSearch();
        $model->load(Yii::$app->request->queryParams);
        $model->setDefaultValues();

        $dataProvider = $model->search(Yii::$app->request->queryParams);

        // Obtener la data del DataProvider por adelantado para uso posterior
        $data = $dataProvider->getModels();

        $categorias = GastoService::getCategoriasList();

        // Check if it's an AJAX request
        if (Yii::$app->request->isAjax) {
            return $this->renderPartial('_dashboard/_grid', [
                'data' => $data,
                'dataProvider' => $dataProvider,
                'searchModel' => $model,
                'categorias' => $categorias,
            ]);
        }

        return $this->render('_dashboard/index', [
            'data' => $data,
            'gastoTotalMes' => GastoService::getTotalDelMes(
                Yii::$app->request->get('GastosCategoriaDashboardSearch')['year'] ?? date('Y'),
                Yii::$app->request->get('GastosCategoriaDashboardSearch')['month'] ?? date('m'),
                null
            ),
            'searchModel' => $model,
            'dataProvider' => $dataProvider,
            'categorias' => $categorias,
        ]);
    }

    /**
     * Busca el modelo GastosCategoria por clave primaria.
     * @param int $id ID
     * @return GastosCategoria
     * @throws NotFoundHttpException si el modelo no existe
     */
    protected function findModel($id)
    {
        if (($model = GastosCategoria::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
