<?php

namespace frontend\controllers;

use Yii;
use common\models\Ingresos\Ingresos;
use common\models\Ingresos\IngresosSearch;
use common\services\IngresoService;
use common\services\ProyectoService;
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
     * Lists all Ingresos models. Requiere proyecto_id en la URL.
     * @param int $proyecto_id
     * @return mixed
     */
    public function actionIndex($proyecto_id)
    {
        $proyecto = ProyectoService::ensureUserCanAccessProyecto((int) $proyecto_id);

        $searchModel = new IngresosSearch();
        $params = Yii::$app->request->queryParams;
        if (!isset($params['IngresosSearch'])) {
            $params['IngresosSearch'] = [];
        }
        $params['IngresosSearch']['proyecto_id'] = $proyecto_id;
        $params['proyecto_id'] = $proyecto_id;
        $searchModel->load($params);
        $searchModel->setDefaultValues();

        $dataProvider = $searchModel->search($params);
        $getSearch = Yii::$app->request->get('IngresosSearch', []);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'totalIngresoMes' => IngresoService::getTotalDelMes(
                $getSearch['year'] ?? date('Y'),
                $getSearch['month'] ?? date('m'),
                (int) $proyecto_id
            ),
            'proyecto' => $proyecto,
        ]);
    }

    /**
     * Displays a single Ingresos model.
     * @param int $id ID
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $proyecto = null;
        if ($model->proyecto_id) {
            $proyecto = ProyectoService::ensureUserCanAccessProyecto((int) $model->proyecto_id);
        }
        return $this->render('view', [
            'model' => $model,
            'proyecto' => $proyecto,
        ]);
    }

    /**
     * Creates a new Ingresos model. Requiere proyecto_id en la URL.
     * @param int $proyecto_id
     * @return mixed
     */
    public function actionCreate($proyecto_id)
    {
        $proyecto = ProyectoService::ensureUserCanAccessProyecto((int) $proyecto_id);

        $model = new Ingresos();
        $model->user_id = Yii::$app->user->id;
        $model->proyecto_id = $proyecto_id;

        if ($model->load(Yii::$app->request->post())) {
            $model->adjuntoFile = \yii\web\UploadedFile::getInstance($model, 'adjuntoFile');
            if ($model->save()) {
                $model->saveAdjuntoFile();
                return $this->redirect(['view', 'id' => $model->id, 'proyecto_id' => $proyecto_id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'proyecto' => $proyecto,
        ]);
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
        $proyecto = null;
        if ($model->proyecto_id) {
            $proyecto = ProyectoService::ensureUserCanAccessProyecto((int) $model->proyecto_id);
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->adjuntoFile = \yii\web\UploadedFile::getInstance($model, 'adjuntoFile');
            if ($model->save()) {
                $model->saveAdjuntoFile();
                $redirect = ['view', 'id' => $model->id];
                if ($model->proyecto_id) {
                    $redirect['proyecto_id'] = $model->proyecto_id;
                }
                return $this->redirect($redirect);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'proyecto' => $proyecto,
        ]);
    }

    /**
     * Deletes an existing Ingresos model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model->proyecto_id) {
            ProyectoService::ensureUserCanAccessProyecto((int) $model->proyecto_id);
        }
        $proyectoId = $model->proyecto_id;
        $model->delete();

        $redirect = ['index'];
        if ($proyectoId) {
            $redirect['proyecto_id'] = $proyectoId;
        }
        return $this->redirect($redirect);
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
