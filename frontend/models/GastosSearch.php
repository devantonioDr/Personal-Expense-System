<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Gastos;

/**
 * GastosSearch represents the model behind the search form of `frontend\models\Gastos`.
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
            // [['id', 'user_id', 'created_at', 'updated_at'], 'integer'],
            [['year','month','descripcion'], 'safe'],
            // [['monto'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    public function baseQuery(){
        return Gastos::find();
    }

    public function setDefaultValues(){
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
    public function search($params,$user_id = null)
    {
        $query = $this->baseQuery();

        if($user_id){
            $query->where(['user_id' => $user_id]);
        }

        $this->setDefaultValues();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        // $query->andFilterWhere([
        //     'id' => $this->id,
        //     'user_id' => $this->user_id,
        //     'monto' => $this->monto,
        //     'created_at' => $this->created_at,
        //     'updated_at' => $this->updated_at,
        // ]);

        // $query->andFilterWhere(['like', 'descripcion', $this->descripcion]);

        return $dataProvider;
    }
}
