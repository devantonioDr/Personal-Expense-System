<?php

namespace common\models\Gastos;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Gastos\Gastos;

/**
 * GastosSearch represents the model behind the search form of `common\models\Gastos\Gastos`.
 */
class GastosSearch extends Gastos
{
    public $year = null;
    public $month = null;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'created_at', 'updated_at'], 'integer'],
            [['descripcion'], 'safe'],
            [['monto'], 'number'],
            [['year', 'month'], 'integer'], // Añade reglas para año y mes
            [['categoria_nombre','categoria_id'], 'safe'], // Añadir regla para categoria_nombre
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
        return Gastos::find()->leftJoin(
            'categorias_gastos',
            'categorias_gastos.id = gastos.categoria_id'
        )->select(['gastos.*', 'categorias_gastos.nombre as categoria_nombre']);
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
            $query->where(['user_id' => $user_id]);
        }

        $this->setDefaultValues();

        $attributes = array_keys($this->getAttributes());


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['attributes' => array_merge($attributes, ['categoria_nombre']), 'defaultOrder' => ['id' => SORT_DESC]],
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

        // Filtrar por descripción
        $query->andFilterWhere(['like', 'descripcion', $this->descripcion]);

        // Filtrar por nombre de categoría
        $query->andFilterWhere(['like', 'categorias_gastos.nombre', $this->categoria_nombre]);

        $query->andFilterWhere([
            'categoria_id' => $this->categoria_id, // Filtro por categoría
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
        $query->andWhere(['>=', 'fecha_pago', $startOfMonth])
            ->andWhere(['<=', 'fecha_pago', $endOfMonth]);
    }
}
