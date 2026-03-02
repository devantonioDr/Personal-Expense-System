<?php

namespace common\models\Gastos;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * GastosCategoriaDashboardSearch representa el modelo de búsqueda para el dashboard de categorías de gastos.
 */
class GastosCategoriaDashboardSearch extends GastosCategoria
{
    public $year;
    public $month;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['nombre', 'descripcion'], 'safe'],
            [['year', 'month'], 'safe'],
            [['total_gastos', 'gastos_count'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    public function baseQuery()
    {
        return GastosCategoria::find()->select(['gastos_categoria.*', 'SUM(gastos.monto) AS total_gastos'])
            ->from('gastos_categoria')
            ->leftJoin('gastos', 'gastos_categoria.id = gastos.categoria_id')
            ->groupBy('gastos_categoria.id');
    }

    public function setDefaultValues()
    {
        if (empty($this->month)) {
            $this->month = date('m');
        }
        if (empty($this->year)) {
            $this->year = date('Y');
        }
    }

    /**
     * @param array $params
     * @param int|null $user_id
     * @return ActiveDataProvider
     */
    public function search($params, $user_id = null)
    {
        $query = $this->baseQuery();

        if ($user_id) {
            $query->andWhere(['gastos.user_id' => $user_id]);
        }

        $this->setDefaultValues();

        $attributes = array_keys($this->getAttributes());

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['attributes' => array_merge($attributes, ['total_gastos']), 'defaultOrder' => ['id' => SORT_DESC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        if ($this->year && $this->month) {
            $this->applyDateFilter($query);
        }

        $query->andFilterWhere(['like', 'gastos_categoria.nombre', $this->nombre])
            ->andFilterWhere(['like', 'gastos_categoria.descripcion', $this->descripcion])
            ->andFilterWhere(['gastos_categoria.id' => $this->id]);

        return $dataProvider;
    }

    /**
     * @param \yii\db\ActiveQuery $query
     */
    protected function applyDateFilter($query)
    {
        $startOfMonth = date('Y-m-d', mktime(0, 0, 0, $this->month, 1, $this->year));
        $endOfMonth = date('Y-m-d', mktime(23, 59, 59, $this->month, date('t', mktime(0, 0, 0, $this->month, 1, $this->year)), $this->year));
        $query->andWhere(['>=', 'gastos.fecha_pago', $startOfMonth])
            ->andWhere(['<=', 'gastos.fecha_pago', $endOfMonth]);
    }
}
