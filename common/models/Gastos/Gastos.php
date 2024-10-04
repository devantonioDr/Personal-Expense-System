<?php

namespace common\models\Gastos;

use common\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "gastos".
 *
 * @property int $id
 * @property int $user_id
 * @property string $descripcion
 * @property float $monto
 * @property string $fecha_pago  // Nueva propiedad para la fecha de pago
 * @property int $created_at
 * @property int $updated_at
 * @property int|null $categoria_id  // Nueva propiedad para la categoría

 * @property User $user
 * @property CategoriasGastos $categoria // Relación con la categoría
 */
class Gastos extends \yii\db\ActiveRecord
{

    public $year; // Añade year y month si es necesario
    public $month;
    public $categoria_nombre; // Propiedad para el nombre de la categoría

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'gastos';
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::class,
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'descripcion', 'monto', 'fecha_pago'], 'required'],
            [['user_id', 'created_at', 'updated_at', 'categoria_id'], 'integer'],
            [['monto'], 'number'],
            [['descripcion'], 'string', 'max' => 255],
            [['fecha_pago'], 'date', 'format' => 'php:Y-m-d'], // Validación para la fecha
            [['categoria_id'], 'exist', 'skipOnError' => true, 'targetClass' => CategoriasGastos::class, 'targetAttribute' => ['categoria_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'ID de Usuario',
            'descripcion' => 'Descripción',
            'monto' => 'Monto',
            'fecha_pago' => 'Fecha de Pago', // Etiqueta para la nueva propiedad
            'categoria_id' => 'Categoría', // Etiqueta para la nueva propiedad
            'created_at' => 'Creado en',
            'updated_at' => 'Actualizado en',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * Gets query for [[Categoria]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategoria()
    {
        return $this->hasOne(CategoriasGastos::class, ['id' => 'categoria_id']);
    }
}
