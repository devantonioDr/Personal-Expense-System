<?php

namespace common\models\Gastos;

use common\models\Proyecto\Proyecto;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Modelo para la tabla `gastos_categoria`.
 * Las categorías se almacenan siempre en esta tabla.
 *
 * @property int $id
 * @property string $nombre
 * @property string|null $descripcion
 * @property int $created_at
 * @property int $updated_at
 * @property string|null $color_etiqueta
 * @property int|null $proyecto_id
 *
 * @property Gastos[] $gastos
 * @property Proyecto $proyecto
 */
class GastosCategoria extends ActiveRecord
{
    public $total_gastos;
    public $gastos_count;

    public static function tableName()
    {
        return 'gastos_categoria';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public function rules()
    {
        return [
            [['nombre', 'color_etiqueta'], 'required'],
            [['gastos_count', 'proyecto_id'], 'integer'],
            [['nombre'], 'string', 'max' => 100],
            [['proyecto_id'], 'exist', 'skipOnError' => true, 'targetClass' => Proyecto::class, 'targetAttribute' => ['proyecto_id' => 'id']],
            [['descripcion'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'gastos_count' => 'Total Gastos',
            'id' => 'ID',
            'nombre' => 'Nombre',
            'descripcion' => 'Descripcion',
            'color_etiqueta' => 'Color Etiqueta',
            'created_at' => 'Fecha de creación',
            'updated_at' => 'Updated At',
            'proyecto_id' => 'Proyecto',
        ];
    }

    public function getGastos()
    {
        return $this->hasMany(Gastos::class, ['categoria_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProyecto()
    {
        return $this->hasOne(Proyecto::class, ['id' => 'proyecto_id']);
    }
}
