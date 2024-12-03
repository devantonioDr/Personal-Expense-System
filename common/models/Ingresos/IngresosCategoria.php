<?php

namespace common\models\Ingresos;

use Yii;

/**
 * This is the model class for table "ingresos_categoria".
 *
 * @property int $id
 * @property string $nombre
 * @property string|null $descripcion
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Ingresos[] $ingresos
 */
class IngresosCategoria extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ingresos_categoria';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre', 'created_at', 'updated_at'], 'required'],
            [['descripcion'], 'string'],
            [['created_at', 'updated_at'], 'integer'],
            [['nombre'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombre' => 'Nombre',
            'descripcion' => 'Descripcion',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Ingresos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIngresos()
    {
        return $this->hasMany(Ingresos::class, ['categoria_id' => 'id']);
    }
}
