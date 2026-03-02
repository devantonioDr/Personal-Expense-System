<?php

namespace frontend\controllers;

use Yii;
use common\models\Gastos\Gastos;
use common\models\Gastos\GastosSearch;
use common\services\GastoService;
use common\services\IngresoService;
use common\services\ProyectoService;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\web\BadRequestHttpException;
use yii\web\HttpException;

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
     * Lists all Gastos models. Requiere proyecto_id en la URL.
     * @param int $proyecto_id
     * @return mixed
     */
    public function actionIndex($proyecto_id)
    {
        $proyecto = ProyectoService::ensureUserCanAccessProyecto((int) $proyecto_id);

        $model = new GastosSearch();
        $params = Yii::$app->request->queryParams;
        if (!isset($params['GastosSearch'])) {
            $params['GastosSearch'] = [];
        }
        $params['GastosSearch']['proyecto_id'] = $proyecto_id;
        $params['proyecto_id'] = $proyecto_id;
        $model->load($params);
        $model->setDefaultValues();

        $dataProvider = $model->search($params);

        if (Yii::$app->request->isAjax) {
            return $this->renderPartial('_grid', [
                'dataProvider' => $dataProvider,
                'searchModel' => $model,
                'categorias' => GastoService::getCategoriasList(),
            ]);
        }
        $getSearch = Yii::$app->request->get('GastosSearch', []);
        return $this->render('index', [
            'gastoTotalHoy' => GastoService::getTotalHoy((int) $proyecto_id),
            'gastoTotalMes' => GastoService::getTotalDelMes(
                $getSearch['year'] ?? date('Y'),
                $getSearch['month'] ?? date('m'),
                (int) $proyecto_id
            ),
            'totalIngresoMes' => IngresoService::getTotalDelMes(
                $getSearch['year'] ?? date('Y'),
                $getSearch['month'] ?? date('m'),
                (int) $proyecto_id
            ),
            'searchModel' => $model,
            'dataProvider' => $dataProvider,
            'categorias' => GastoService::getCategoriasList(),
            'proyecto' => $proyecto,
        ]);
    }

    /**
     * Displays a single Gastos model.
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
     * Creates a new Gastos model. Requiere proyecto_id en la URL.
     * @param int $proyecto_id
     * @return mixed
     */
    public function actionCreate($proyecto_id)
    {
        $proyecto = ProyectoService::ensureUserCanAccessProyecto((int) $proyecto_id);

        $model = new Gastos();
        $model->user_id = Yii::$app->user->id;
        $model->proyecto_id = $proyecto_id;

        if ($model->load(Yii::$app->request->post())) {
            $model->adjuntoFile = \yii\web\UploadedFile::getInstance($model, 'adjuntoFile');
            $this->resolveCategoriaFromNombre($model);
            if ($model->save()) {
                $model->saveAdjuntoFile();
                Yii::$app->session->setFlash('success', 'Gasto registrado correctamente.');
                return $this->redirect(['index', 'proyecto_id' => $proyecto_id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'sugerenciasCategorias' => GastoService::getCategoriasParaProyectoOGlobal(),
            'proyecto' => $proyecto,
        ]);
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
        $proyecto = null;
        if ($model->proyecto_id) {
            $proyecto = ProyectoService::ensureUserCanAccessProyecto((int) $model->proyecto_id);
        }
        $proyectoIdForCat = $model->proyecto_id ? (int) $model->proyecto_id : null;

        if ($model->load(Yii::$app->request->post())) {
            $model->adjuntoFile = \yii\web\UploadedFile::getInstance($model, 'adjuntoFile');
            $this->resolveCategoriaFromNombre($model);
            if ($model->save()) {
                $model->saveAdjuntoFile();
                Yii::$app->session->setFlash('success', 'Gasto actualizado correctamente.');
                $redirect = ['update', 'id' => $model->id];
                if ($proyectoIdForCat) {
                    $redirect['proyecto_id'] = $proyectoIdForCat;
                }
                return $this->redirect($redirect);
            }
        }
        return $this->render('update', [
            'model' => $model,
            'sugerenciasCategorias' => GastoService::getCategoriasParaProyectoOGlobal(),
            'proyecto' => $proyecto,
        ]);
    }

    /**
     * Deletes an existing Gastos model.
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
     * API: busca categorías solo por nombre. Globales (sin filtrar por proyecto).
     * @return array
     */
    public function actionSearchCategorias()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $q = trim((string) Yii::$app->request->get('q', ''));
        return GastoService::searchCategoriasByNombre($q);
    }

    public function actionUpdateCategory()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        try {
            $data = json_decode(Yii::$app->request->getRawBody(), true);

            if (!isset($data['gastoId'], $data['newCategoryId'])) {
                throw new BadRequestHttpException('Parámetros incompletos.');
            }

            $gasto = Gastos::findOne($data['gastoId']);
            if (!$gasto) {
                throw new NotFoundHttpException('Gasto no encontrado.');
            }
            if ($gasto->proyecto_id) {
                ProyectoService::ensureUserCanAccessProyecto((int) $gasto->proyecto_id);
            }

            return GastoService::updateCategory($data['gastoId'], $data['newCategoryId']);
        } catch (HttpException $e) {
            Yii::$app->response->statusCode = $e->statusCode;
            return ['success' => false, 'message' => $e->getMessage()];
        } catch (\Exception $e) {
            Yii::$app->response->statusCode = 500;
            return ['success' => false, 'message' => 'Error interno del servidor.'];
        }
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
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Si el modelo tiene categoria_nombre, busca o crea la categoría y asigna categoria_id.
     */
    protected function resolveCategoriaFromNombre(Gastos $model)
    {
        $nombre = trim((string) ($model->categoria_nombre ?? ''));
        if ($nombre === '') {
            return;
        }
        $model->categoria_id = GastoService::getOrCreateCategoriaId($nombre);
    }
}
