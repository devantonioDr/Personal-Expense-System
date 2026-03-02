<?php

namespace common\models\Ingresos;

use common\models\User;
use common\models\Proyecto\Proyecto;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\web\UploadedFile;

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
 * @property int|null $proyecto_id
 * @property int|null $created_by
 * @property int $deleted
 * @property string|null $adjunto Ruta relativa del archivo adjunto (foto)
 *
 * @property IngresosCategoria $categoria
 * @property User $user
 * @property User $createdByUser
 * @property Proyecto $proyecto
 */
class Ingresos extends \yii\db\ActiveRecord
{
    /** @var UploadedFile|null Archivo de imagen subido (no se persiste en BD) */
    public $adjuntoFile;

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
    public static function find()
    {
        return parent::find()->andWhere(['deleted' => 0]);
    }

    /**
     * Soft delete: marca deleted = 1 en lugar de borrar el registro.
     * {@inheritdoc}
     */
    public function delete()
    {
        $this->deleted = 1;
        return $this->save(false);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'descripcion', 'monto', 'fecha_pago'], 'required'],
            [['user_id', 'created_at', 'updated_at', 'categoria_id', 'proyecto_id', 'created_by', 'deleted'], 'integer'],
            [['monto'], 'number'],
            [['fecha_pago'], 'safe'],
            [['descripcion'], 'string', 'max' => 255],
            [['categoria_id'], 'exist', 'skipOnError' => true, 'targetClass' => IngresosCategoria::class, 'targetAttribute' => ['categoria_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['proyecto_id'], 'exist', 'skipOnError' => true, 'targetClass' => Proyecto::class, 'targetAttribute' => ['proyecto_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
            [['adjunto'], 'string', 'max' => 512],
            [['adjuntoFile'], 'file', 'skipOnEmpty' => true, 'extensions' => ['png', 'jpg', 'jpeg', 'gif', 'webp'], 'maxSize' => 5 * 1024 * 1024, 'checkExtensionByMimeType' => true],
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
            'proyecto_id' => 'Proyecto',
            'created_by' => 'Creado por',
            'deleted' => 'Eliminado',
            'adjunto' => 'Foto / Comprobante',
            'adjuntoFile' => 'Adjuntar foto',
        ];
    }

    /**
     * Ruta base (directorio) para guardar adjuntos de ingresos del proyecto actual.
     * Estructura: uploads/proyectos/[id]/ingresos
     * @return string
     */
    public function getAdjuntosBasePath()
    {
        $proyectoId = (int) ($this->proyecto_id ?? 0);
        return Yii::getAlias('@frontend/web/uploads/proyectos/' . $proyectoId . '/ingresos');
    }

    /**
     * URL base para servir adjuntos de ingresos del proyecto actual.
     * @return string
     */
    public function getAdjuntosBaseUrl()
    {
        $proyectoId = (int) ($this->proyecto_id ?? 0);
        return Yii::getAlias('@web/uploads/proyectos/' . $proyectoId . '/ingresos');
    }

    /**
     * URL pública del adjunto si existe.
     * Compatible con rutas antiguas (solo nombre) y nuevas (proyectos/id/ingresos/...).
     * @return string|null
     */
    public function getAdjuntoUrl()
    {
        if (empty($this->adjunto)) {
            return null;
        }
        if (strpos($this->adjunto, 'proyectos/') === 0) {
            return Yii::getAlias('@web/uploads/' . $this->adjunto);
        }
        return $this->getAdjuntosBaseUrl() . '/' . $this->adjunto;
    }

    /**
     * Guarda el archivo subido (adjuntoFile) en el directorio de adjuntos y asigna $this->adjunto.
     * Estructura: proyectos/[proyecto_id]/ingresos/[filename]
     * Debe llamarse después de que el modelo tenga id (save previo).
     * @return bool true si se guardó un archivo o no había archivo; false si falló.
     */
    public function saveAdjuntoFile()
    {
        $file = UploadedFile::getInstance($this, 'adjuntoFile');
        if (!$file) {
            return true;
        }
        $dir = $this->getAdjuntosBasePath();
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        $ext = strtolower($file->extension);
        $name = $this->id . '_' . time() . '_' . substr(md5(uniqid('', true)), 0, 8) . '.' . $ext;
        if ($file->saveAs($dir . '/' . $name)) {
            $oldPath = $this->getAdjuntoFullPath();
            if ($oldPath && is_file($oldPath)) {
                @unlink($oldPath);
            }
            $proyectoId = (int) ($this->proyecto_id ?? 0);
            $this->adjunto = 'proyectos/' . $proyectoId . '/ingresos/' . $name;
            return $this->save(false);
        }
        return false;
    }

    /**
     * Ruta absoluta en disco del adjunto actual (para borrar el archivo antiguo).
     * @return string|null
     */
    protected function getAdjuntoFullPath()
    {
        if (empty($this->adjunto)) {
            return null;
        }
        if (strpos($this->adjunto, 'proyectos/') === 0) {
            return Yii::getAlias('@frontend/web/uploads/' . $this->adjunto);
        }
        return $this->getAdjuntosBasePath() . '/' . $this->adjunto;
    }

    /**
     * {@inheritdoc}
     */
    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        if ($insert && !$this->created_by && !Yii::$app->user->isGuest) {
            $this->created_by = Yii::$app->user->id;
        }
        return true;
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProyecto()
    {
        return $this->hasOne(Proyecto::class, ['id' => 'proyecto_id']);
    }

    /**
     * Usuario que insertó el registro.
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedByUser()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }
}
