<?php

namespace common\models\Proyecto;

use common\models\User;
use Yii;

/**
 * Usuarios con permiso para usar un proyecto (tabla pivote proyecto_usuario).
 *
 * @property int $id
 * @property int $proyecto_id
 * @property int $user_id
 *
 * @property Proyecto $proyecto
 * @property User $user
 */
class ProyectoUsuario extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'proyecto_usuario';
    }

    public function rules()
    {
        return [
            [['proyecto_id', 'user_id'], 'required'],
            [['proyecto_id', 'user_id'], 'integer'],
            [['proyecto_id', 'user_id'], 'unique', 'targetAttribute' => ['proyecto_id', 'user_id']],
            [['proyecto_id'], 'exist', 'skipOnError' => true, 'targetClass' => Proyecto::class, 'targetAttribute' => ['proyecto_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'proyecto_id' => 'Proyecto',
            'user_id' => 'Usuario',
        ];
    }

    public function getProyecto()
    {
        return $this->hasOne(Proyecto::class, ['id' => 'proyecto_id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
