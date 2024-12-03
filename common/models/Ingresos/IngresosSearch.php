<?php

namespace common\models\Ingresos;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * IngresosSearch represents the model behind the search form of `common\models\Ingresos\Ingresos`.
 */
class IngresosSearch extends Ingresos
{
    public $year = null;
    public $month = null;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'created_at', 'updated_at', 'categoria_id'], 'integer'],
            [['descripcion', 'fecha_pago'], 'safe'],
            [['year', 'month'], 'integer'], // Añade reglas para año y mes
            [['monto'], 'number'],
        ];
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
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
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
        $query = Ingresos::find();

        if ($user_id) {
            $query->where(['user_id' => $user_id]);
        }

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }


        // Filtrar por fecha si se proporciona
        if ($this->year && $this->month) {
            $this->applyDateFilter($query);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'monto' => $this->monto,
            'fecha_pago' => $this->fecha_pago,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'categoria_id' => $this->categoria_id,
        ]);

        $query->andFilterWhere(['like', 'descripcion', $this->descripcion]);

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
