<?php

namespace frontend\controllers;

use Yii;
use common\models\Proyecto\Proyecto;
use common\models\Proyecto\ProyectoUsuario;
use common\models\User;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * ProyectoController implementa las acciones CRUD para el modelo Proyecto.
 */
class ProyectoController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => false,
                        'actions' => ['index'],
                        'roles' => ['@'],
                        'matchCallback' => function () {
                            $identity = Yii::$app->user->identity;
                            return $identity && $identity->user_rol === \common\models\User::ROLE_OPERADOR;
                        },
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lista los proyectos del usuario actual.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Proyecto::find()
                ->andWhere(['user_id' => Yii::$app->user->id])
                ->orderBy(['nombre' => SORT_ASC])
                ->with(['user', 'usuariosConPermiso']),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Muestra un proyecto.
     * @param int $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Crea un nuevo proyecto.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Proyecto();
        $model->user_id = Yii::$app->user->id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Proyecto creado correctamente.');
            return $this->redirect(['update', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Actualiza un proyecto existente y los usuarios con permiso.
     * @param int $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $userIds = (array) (Yii::$app->request->post('user_ids', []));
            $this->syncProyectoUsuarios($model->id, $userIds);
            Yii::$app->session->setFlash('success', 'Proyecto actualizado correctamente.');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $assignedUserIds = array_map('intval', $model->getUsuariosConPermiso()->select('id')->column());
        $assignedUsers = $model->getUsuariosConPermiso()->select(['id', 'username'])->asArray()->all();
        $allUsers = User::find()->select(['username'])->indexBy('id')->column();
        $usersNotInProject = array_diff_key($allUsers, array_flip($assignedUserIds));
        // Excluir al dueño del proyecto del select para no duplicar
        unset($usersNotInProject[$model->user_id]);

        return $this->render('update', [
            'model' => $model,
            'assignedUserIds' => $assignedUserIds,
            'assignedUsers' => $assignedUsers,
            'usersForSelect' => $usersNotInProject,
        ]);
    }

    /**
     * Sincroniza los registros proyecto_usuario con la lista de user_ids.
     * @param int $proyectoId
     * @param array $userIds
     */
    protected function syncProyectoUsuarios(int $proyectoId, array $userIds): void
    {
        $userIds = array_unique(array_filter(array_map('intval', $userIds)));
        $current = ProyectoUsuario::find()->where(['proyecto_id' => $proyectoId])->select('user_id')->column();
        $toAdd = array_diff($userIds, $current);
        $toRemove = array_diff($current, $userIds);
        foreach ($toRemove as $uid) {
            ProyectoUsuario::deleteAll(['proyecto_id' => $proyectoId, 'user_id' => $uid]);
        }
        foreach ($toAdd as $uid) {
            $pu = new ProyectoUsuario();
            $pu->proyecto_id = $proyectoId;
            $pu->user_id = $uid;
            $pu->save(false);
        }
    }

    /**
     * Elimina un proyecto.
     * @param int $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', 'Proyecto eliminado correctamente.');
        return $this->redirect(['index']);
    }

    /**
     * Busca el modelo Proyecto por id y verifica que pertenezca al usuario actual.
     * @param int $id
     * @return Proyecto
     * @throws NotFoundHttpException si no existe o no pertenece al usuario
     */
    protected function findModel($id)
    {
        $model = Proyecto::findOne(['id' => $id, 'user_id' => Yii::$app->user->id]);
        if ($model !== null) {
            return $model;
        }
        throw new NotFoundHttpException('La página solicitada no existe.');
    }
}
