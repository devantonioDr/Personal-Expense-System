<?php

namespace common\models\Proyecto;

use common\models\User;
use common\models\Gastos\Gastos;
use common\models\Gastos\GastosCategoria;
use common\models\Ingresos\Ingresos;
use common\models\Ingresos\IngresosCategoria;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * Modelo para la tabla `proyecto`.
 *
 * @property int $id
 * @property int $user_id
 * @property string $nombre
 * @property int $deleted 0 = activo, 1 = borrado lógico
 * @property int $created_at
 * @property int $updated_at
 *
 * @property User $user
 * @property Gastos[] $gastos
 * @property GastosCategoria[] $gastosCategorias
 * @property Ingresos[] $ingresos
 * @property IngresosCategoria[] $ingresosCategorias
 * @property ProyectoUsuario[] $proyectoUsuarios
 * @property User[] $usuariosConPermiso
 */
class Proyecto extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'proyecto';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
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
            [['user_id', 'nombre'], 'required'],
            [['user_id', 'created_at', 'updated_at', 'deleted'], 'integer'],
            [['nombre'], 'string', 'max' => 255],
            [['deleted'], 'in', 'range' => [0, 1]],
            [['deleted'], 'default', 'value' => 0],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     * Por defecto solo se devuelven proyectos no borrados (deleted = 0).
     */
    public static function find()
    {
        return parent::find()->andWhere(['deleted' => 0]);
    }

    /**
     * Borrado lógico: marca deleted = 1 en lugar de eliminar el registro.
     * Evita que gastos/ingresos queden huérfanos de proyecto.
     * @return false|int
     */
    public function delete()
    {
        $this->deleted = 1;
        return $this->save(false);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Usuario',
            'nombre' => 'Nombre',
            'deleted' => 'Eliminado',
            'created_at' => 'Creado en',
            'updated_at' => 'Actualizado en',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGastos()
    {
        return $this->hasMany(Gastos::class, ['proyecto_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGastosCategorias()
    {
        return $this->hasMany(GastosCategoria::class, ['proyecto_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIngresos()
    {
        return $this->hasMany(Ingresos::class, ['proyecto_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIngresosCategorias()
    {
        return $this->hasMany(IngresosCategoria::class, ['proyecto_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProyectoUsuarios()
    {
        return $this->hasMany(ProyectoUsuario::class, ['proyecto_id' => 'id']);
    }

    /**
     * Usuarios con permiso para usar este proyecto (vía tabla proyecto_usuario).
     * @return \yii\db\ActiveQuery
     */
    public function getUsuariosConPermiso()
    {
        return $this->hasMany(User::class, ['id' => 'user_id'])->viaTable('proyecto_usuario', ['proyecto_id' => 'id']);
    }
}
