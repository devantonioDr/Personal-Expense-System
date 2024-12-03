<?php

namespace common\models\Ingresos;

use common\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "ingresos".
 *
 * @property int $id
 * @property int $user_id
 * @property string $descripcion
 * @property float $monto
 * @property string $fecha_pago
 * @property int $created_at
 * @property int $updated_at
 * @property int|null $categoria_id
 *
 * @property IngresosCategoria $categoria
 * @property User $user
 */
class Ingresos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ingresos';
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
            [['fecha_pago'], 'safe'],
            [['descripcion'], 'string', 'max' => 255],
            [['categoria_id'], 'exist', 'skipOnError' => true, 'targetClass' => IngresosCategoria::class, 'targetAttribute' => ['categoria_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
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
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'descripcion' => 'Descripcion',
            'monto' => 'Monto',
            'fecha_pago' => 'Fecha Pago',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'categoria_id' => 'Categoria ID',
        ];
    }

    /**
     * Gets query for [[Categoria]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategoria()
    {
        return $this->hasOne(IngresosCategoria::class, ['id' => 'categoria_id']);
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
}
