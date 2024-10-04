<?php

namespace common\models\Gastos;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Gastos\CategoriasGastos;
use yii\helpers\VarDumper;

/**
 * CategoriasGastosSearch represents the model behind the search form of `common\models\Gastos\CategoriasGastos`.
 */
class CategoriasGastosSearch extends CategoriasGastos
{
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['nombre', 'descripcion'], 'safe'],
            [['created_at'], 'date', 'format' => 'php:Y-m-d'],
            [['gastos_count'], 'integer'], // Asegúrate de que se pueda filtrar
        ];
    }

    public function search($params)
    {
        $query = CategoriasGastos::find()
            ->select(['categorias_gastos.*', 'COUNT(gastos.id) AS gastos_count'])
            ->leftJoin('gastos', 'gastos.categoria_id = categorias_gastos.id')
            ->groupBy('categorias_gastos.id');

        $attributes = array_keys($this->getAttributes());

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['attributes' => array_merge($attributes, ['gastos_count'])],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        // Filtros
        $query->andFilterWhere(['like', 'nombre', $this->nombre])
            ->andFilterWhere(['like', 'descripcion', $this->descripcion]);

        return $dataProvider;
    }
}
