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
 * AuditProject
 * @package bedezign\yii2\audit\models
 *
 * @property int               $id
 * @property int               $author_id
 * @property string            $name
 * @property string            $description
 * @property int               $status
 * @property int               $created_at
 * @property int               $updated_at
 *
 * @property AuditApplication[] $applications
 * @property Model             $author

 */
class AuditProject extends ActiveRecord
{
    /**
     * @var bool
     */
    protected $autoSerialize = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%audit_project}}';
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
            'name'           => Yii::t('audit', 'Project name'),
            'description'    => Yii::t('audit', 'Description'),
            'status'         => Yii::t('audit', 'Status'),
            'created_at'     => Yii::t('audit', 'Created at'),
            'updated_at'     => Yii::t('audit', 'Updated at'),
        ];
    }

    /**
     * @return bool
     */
    public function hasRelatedData()
    {
        if ($this->getApplications()->count()) {
            return true;
        }
        return false;
    }

    public function getApplications()
    {
        return $this->hasMany(AuditApplcation::class, ['project_id' => 'id']);
    }
}
