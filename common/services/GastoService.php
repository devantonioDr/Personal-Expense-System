<?php

namespace common\services;

use common\models\Gastos\Gastos;
use common\models\Gastos\GastosCategoria;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use yii\db\Expression;
use yii\helpers\VarDumper;


class GastoService
{
    /**
     * Total de gastos del día para el usuario actual (y opcionalmente un proyecto).
     * @param int|null $proyectoId
     * @param int|null $userId Si null, usa el usuario logueado.
     * @return float
     */
    public static function getTotalHoy(?int $proyectoId = null, ?int $userId = null): float
    {
        // $userId = $userId ?? (Yii::$app->user->isGuest ? null : (int) Yii::$app->user->id);
        $currentTimestamp = time();
        $startOfDay = strtotime('midnight', $currentTimestamp);
        $endOfDay = strtotime('tomorrow', $startOfDay) - 1;

        $query = Gastos::find()
            ->select(['SUM(monto)'])
            ->where(['>=', 'fecha_pago', date('Y-m-d', $startOfDay)])
            ->andWhere(['<=', 'fecha_pago', date('Y-m-d', $endOfDay)])
            ->andWhere(['deleted' => 0]);
        if ($userId !== null) {
            $query->andWhere(['user_id' => $userId]);
        }
        if ($proyectoId !== null) {
            $query->andWhere(['proyecto_id' => $proyectoId]);
        }
        $totalGasto = $query->scalar();
        return $totalGasto ? (float) $totalGasto : 0.0;
    }

    /**
     * Total de gastos del mes para el usuario actual (y opcionalmente un proyecto).
     * @param int|null $year
     * @param int|null $month
     * @param int|null $proyectoId
     * @param int|null $userId Si null, usa el usuario logueado.
     * @return float
     */
    public static function getTotalDelMes($year = null, $month = null, ?int $proyectoId = null, ?int $userId = null): float
    {
        // $userId = $userId ?? (Yii::$app->user->isGuest ? null : (int) Yii::$app->user->id);
        $year = $year ?? date('Y');
        $month = $month ?? date('m');

        $startDate = "{$year}-{$month}-01";
        $endDate = date("Y-m-t", strtotime($startDate));

        $query = Gastos::find()
            ->where(['between', 'fecha_pago', $startDate, $endDate])
            ->andWhere(['deleted' => 0]);
        if ($userId !== null) {
            $query->andWhere(['user_id' => $userId]);
        }
        if ($proyectoId !== null) {
            $query->andWhere(['proyecto_id' => $proyectoId]);
        }
        return (float) $query->sum('monto');
    }


    public static function updateCategory($gastoId, $newCategoryId)
    {
        $gasto = Gastos::findOne($gastoId);

        if ($gasto === null) {
            throw new NotFoundHttpException('Gasto no encontrado.');
        }

        $gasto->categoria_id = $newCategoryId;

        if ($gasto->validate() && $gasto->save()) {
            return ['success' => true, 'message' => 'Categoría actualizada correctamente.'];
        }

        // Optionally log the error or inspect $gasto->getErrors()
        throw new ServerErrorHttpException('Error al actualizar la categoría.');
    }

    /**
     * Gastos agrupados por categoría y mes.
     * @param int|null $year
     * @param int|null $proyectoId
     * @param int|null $userId Si null, usa el usuario logueado.
     */
    public function getGastosGroupedByCategoriesAndMonths($year, $proyectoId = null, ?int $userId = null)
    {
        // $userId = $userId ?? (Yii::$app->user->isGuest ? null : (int) Yii::$app->user->id);
        $query = Gastos::find()
            ->select([
                'categoria_id',
                "MONTH(fecha_pago) AS mes",
                new Expression("SUM(monto) AS total")
            ])
            ->andWhere(['YEAR(fecha_pago)' => $year])
            ->andWhere(['deleted' => 0]);
        if ($userId !== null) {
            $query->andWhere(['user_id' => $userId]);
        }
        if ($proyectoId !== null) {
            $query->andWhere(['proyecto_id' => $proyectoId]);
        }
        $result = $query->groupBy(['categoria_id', new Expression("MONTH(fecha_pago)")])
            ->asArray()
            ->all();


        // Formato: [categoria_id][mes] => total
        $datos = [];
        foreach ($result as $row) {
            $datos[$row['categoria_id']][(int)$row['mes']] = (float)$row['total'];
        }
        return $datos;
    }

    // Propiedad estática para almacenar las categorías en caché
    private static $categoriasCache = null;
    public static function obtenerCategorias()
    {
        if (self::$categoriasCache) {
            return self::$categoriasCache;
        }
        // Formato: ['id' => 'nombre']
        self::$categoriasCache = GastosCategoria::find()->select(['nombre'])->indexBy('id')->column();
        return self::$categoriasCache;
    }

    /**
     * Nombres de categorías para datalist. Globales (sin filtrar por proyecto).
     * @return string[]
     */
    public static function getCategoriasParaProyectoOGlobal(): array
    {
        return GastosCategoria::find()
            ->select(['nombre'])
            ->orderBy(['nombre' => SORT_ASC])
            ->column();
    }

    /**
     * Lista id => nombre para dropdowns. Globales (sin filtrar por proyecto).
     * @return array
     */
    public static function getCategoriasList(): array
    {
        return GastosCategoria::find()
            ->orderBy(['nombre' => SORT_ASC])
            ->select(['nombre'])
            ->indexBy('id')
            ->column();
    }

    /**
     * Busca categorías por nombre (solo nombre). Globales.
     * @param string $nombre Parte del nombre a buscar
     * @return array [['id' => int, 'nombre' => string], ...]
     */
    public static function searchCategoriasByNombre(string $nombre): array
    {
        $query = GastosCategoria::find()
            ->select(['id', 'nombre'])
            ->orderBy(['nombre' => SORT_ASC])
            ->limit(15);
        if ($nombre !== '') {
            $query->andWhere(['like', 'nombre', $nombre]);
        }
        $rows = $query->asArray()->all();
        return array_map(fn ($row) => ['id' => (int) $row['id'], 'nombre' => $row['nombre']], $rows);
    }

    /**
     * Obtiene el id de una categoría por nombre (global) o la crea si no existe.
     * @param string $nombre
     * @return int
     */
    public static function getOrCreateCategoriaId(string $nombre): int
    {
        $cat = GastosCategoria::find()
            ->andWhere(['nombre' => $nombre])
            ->andWhere(['proyecto_id' => null])
            ->one();
        if ($cat) {
            return (int) $cat->id;
        }
        $newCat = new GastosCategoria();
        $newCat->nombre = $nombre;
        $newCat->proyecto_id = null;
        $newCat->color_etiqueta = '#3498DB';
        $newCat->save(false);
        return (int) $newCat->id;
    }
}
