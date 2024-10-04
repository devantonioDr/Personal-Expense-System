<?php

namespace common\models\Gastos;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * GastosSearch represents the model behind the search form of `common\models\Gastos\Gastos`.
 */
class CategoriasGastosDashboardSearch extends CategoriasGastos
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
            [['total_gastos', 'gastos_count'], 'number'], // Campo virtual para los gastos totales
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
        return CategoriasGastos::find()->select(['categorias_gastos.*', 'SUM(gastos.monto) AS total_gastos'])
            ->from('categorias_gastos')
            ->leftJoin('gastos', 'categorias_gastos.id = gastos.categoria_id')
            ->groupBy('categorias_gastos.id');
    }

    public function setDefaultValues()
    {
        if (empty($this->month)) {
            $this->month = date('m'); // Set default month to current month (e.g., '03' for March)
        }
        if (empty($this->year)) {
            $this->year = date('Y'); // Set default year to current year (e.g., '2024')
        }
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params, $user_id = null)
    {


        $query = $this->baseQuery();

        if ($user_id) {
            $query->andWhere(['gastos.user_id' => $user_id]);
        }

        // $query->orderBy(['total_gastos' => SORT_DESC]);

        $this->setDefaultValues();

        $attributes = array_keys($this->getAttributes());


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['attributes' => array_merge($attributes, ['total_gastos']), 'defaultOrder' => ['id' => SORT_DESC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // Filtrar por fecha si se proporciona
        if ($this->year && $this->month) {
            $this->applyDateFilter($query);
        }

        $query->andFilterWhere(['like', 'categorias_gastos.nombre', $this->nombre])
            ->andFilterWhere(['like', 'categorias_gastos.descripcion', $this->descripcion]);


        $query->andFilterWhere([
            'categorias_gastos.id' => $this->id,
        ]);


        return $dataProvider;
    }

    /**
     * Aplica el filtro de fechas al query.
     *
     * @param \yii\db\ActiveQuery $query
     */
    protected function applyDateFilter($query)
    {
        // Define las fechas de inicio y fin en formato Y-m-d
        $startOfMonth = date('Y-m-d', mktime(0, 0, 0, $this->month, 1, $this->year));
        $endOfMonth = date('Y-m-d', mktime(23, 59, 59, $this->month, date('t', mktime(0, 0, 0, $this->month, 1, $this->year)), $this->year));

        // Aplica el filtro al query
        $query->andWhere(['>=', 'gastos.fecha_pago', $startOfMonth])
            ->andWhere(['<=', 'gastos.fecha_pago', $endOfMonth]);
    }
}
