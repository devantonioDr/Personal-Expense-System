<?php

namespace common\services;

use common\models\Proyecto\Proyecto;
use Yii;
use yii\db\Query;
use yii\web\NotFoundHttpException;

class ProyectoService
{
    /**
     * Comprueba que el usuario actual pueda acceder al proyecto (es dueño o está en proyecto_usuario).
     * Si no tiene acceso o el proyecto no existe, lanza NotFoundHttpException.
     *
     * @param int $proyectoId
     * @param int|null $userId Si null, usa el usuario logueado.
     * @return Proyecto
     * @throws NotFoundHttpException
     */
    public static function ensureUserCanAccessProyecto(int $proyectoId, ?int $userId = null): Proyecto
    {
        $proyecto = self::getProyectoParaUsuario($proyectoId, $userId);
        if ($proyecto === null) {
            throw new NotFoundHttpException('No tiene permiso para acceder a este proyecto o el proyecto no existe.');
        }
        return $proyecto;
    }

    /**
     * Proyectos del usuario actual ordenados por nombre: los que creó y los que tiene autorizados (proyecto_usuario).
     * @param int|null $userId Si null, usa el usuario logueado.
     * @return Proyecto[]
     */
    public static function getProyectosByUserId(?int $userId = null): array
    {
        $userId = $userId ?? (Yii::$app->user->isGuest ? null : (int) Yii::$app->user->id);
        if ($userId === null) {
            return [];
        }
        $proyectosAutorizados = (new Query())
            ->select('proyecto_id')
            ->from('proyecto_usuario')
            ->where(['user_id' => $userId]);

        return Proyecto::find()
            ->andWhere([
                'or',
                ['user_id' => $userId],
                ['in', 'id', $proyectosAutorizados],
            ])
            ->orderBy(['nombre' => SORT_ASC])
            ->all();
    }

    /**
     * Proyecto por id si el usuario actual es dueño o está autorizado (proyecto_usuario).
     * @param int $id
     * @param int|null $userId Si null, usa el usuario logueado.
     * @return Proyecto|null
     */
    public static function getProyectoParaUsuario(int $id, ?int $userId = null): ?Proyecto
    {
        $userId = $userId ?? (Yii::$app->user->isGuest ? null : (int) Yii::$app->user->id);
        if ($userId === null) {
            return null;
        }
        $proyecto = Proyecto::findOne($id);
        if (!$proyecto) {
            return null;
        }
        if ($proyecto->user_id === (int) $userId) {
            return $proyecto;
        }
        $autorizado = (new Query())
            ->from('proyecto_usuario')
            ->where(['proyecto_id' => $id, 'user_id' => $userId])
            ->exists(Proyecto::getDb());
        return $autorizado ? $proyecto : null;
    }
}
