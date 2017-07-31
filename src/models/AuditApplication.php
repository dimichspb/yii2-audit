<?php

namespace bedezign\yii2\audit\models;

use bedezign\yii2\audit\Audit;
use bedezign\yii2\audit\components\db\ActiveRecord;
use bedezign\yii2\audit\components\Helper;
use Yii;
use yii\base\Model;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\Expression;
use yii\web\User;

/**
 * AuditApplication
 * @package bedezign\yii2\audit\models
 *
 * @property int               $id
 * @property string            $unique_id
 * @property int               $author_id
 * @property int               $project_id
 * @property string            $name
 * @property string            $description
 * @property string            $token
 * @property int               $status
 * @property int               $created_at
 * @property int               $updated_at
 *
 * @property AuditProject      $project
 * @property Model             $author
 * @property AuditEntry[]      $entries

 */
class AuditApplication extends ActiveRecord
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    /**
     * @var bool
     */
    protected $autoSerialize = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%audit_application}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'author_id',
                'updatedByAttribute' => false,
            ],
            [
                'class' => TimestampBehavior::className(),
            ],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id'             => Yii::t('audit', 'Entry ID'),
            'author_id'      => Yii::t('audit', 'Author ID'),
            'author'         => Yii::t('audit', 'Author'),
            'project_id'     => Yii::t('audit', 'Project ID'),
            'project'        => Yii::t('audit', 'Project'),
            'name'           => Yii::t('audit', 'Project name'),
            'description'    => Yii::t('audit', 'Description'),
            'token'          => Yii::t('audit', 'Token'),
            'status'         => Yii::t('audit', 'Status'),
            'created_at'     => Yii::t('audit', 'Created at'),
            'updated_at'     => Yii::t('audit', 'Updated at'),
        ];
    }

    /**
     * @param $unique_id
     * @return AuditApplication
     */
    public static function findByUniqueId($unique_id)
    {
        return self::findOne(['unique_id' => $unique_id]);
    }

    /**
     * @param $unique_id
     * @return AuditApplication
     */
    public static function findOrCreateByUniqueId($unique_id)
    {
        if (!$application = self::findByUniqueId($unique_id)) {
            $application = new AuditApplication([
                'unique_id' => $unique_id,
                'name' => $unique_id,
            ]);
            $application->save();
        }
        return $application;
    }

    /**
     * @return bool
     */
    public function hasRelatedData()
    {
        if ($this->getEntries()->count()) {
            return true;
        }
        return false;
    }

    public function getEntries()
    {
        return $this->hasMany(AuditEntry::class, ['application_id' => 'id']);
    }

    public function getProject()
    {
        return $this->hasOne(AuditProject::className(), ['id' => 'project_id']);
    }

    public function beforeSave($insert)
    {
        if (is_null($this->token)) {
            $this->token = Yii::$app->security->generateRandomString() . '_' . time();
        }
    }
}
