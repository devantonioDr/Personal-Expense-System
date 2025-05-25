<?php

namespace common\models\Gastos;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "categorias_gastos".
 *
 * @property int $id
 * @property string $nombre
 * @property string|null $descripcion
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Gastos[] $gastos
 */
class CategoriasGastos extends ActiveRecord
{
   
    public $total_gastos;
    public $gastos_count;

    public static function tableName()
    {
        return 'categorias_gastos';
    }

    public function rules()
    {
        return [
            [['nombre','color_etiqueta'], 'required'],
            [['gastos_count'], 'integer'],
            [['nombre'], 'string', 'max' => 100],
            [['descripcion'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'gastos_count' => "Total Gastos",
            'id' => 'ID',
            'nombre' => 'Nombre',
            'descripcion' => 'Descripcion',
            'color_etiqueta' => 'Color Etiqueta',	
            'created_at' => 'Fecha de creación',
            'updated_at' => 'Updated At',
        ];
    }

    public function getGastos()
    {
        return $this->hasMany(Gastos::class, ['categoria_id' => 'id']);
    }
}
