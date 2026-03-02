<?php

namespace common\models\Gastos;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * GastosCategoriaSearch representa el modelo detrás del formulario de búsqueda de `common\models\Gastos\GastosCategoria`.
 */
class GastosCategoriaSearch extends GastosCategoria
{
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['nombre', 'descripcion'], 'safe'],
            [['created_at'], 'date', 'format' => 'php:Y-m-d'],
            [['gastos_count'], 'integer'],
        ];
    }

    public function search($params)
    {
        $query = GastosCategoria::find()
            ->select(['gastos_categoria.*', 'COUNT(gastos.id) AS gastos_count'])
            ->leftJoin('gastos', 'gastos.categoria_id = gastos_categoria.id')
            ->groupBy('gastos_categoria.id');

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

        $query->andFilterWhere(['like', 'nombre', $this->nombre])
            ->andFilterWhere(['like', 'descripcion', $this->descripcion]);

        return $dataProvider;
    }
}
