<?php

namespace infoweb\partials\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;
use creocoder\translateable\TranslateableBehavior;

/**
 * This is the model class for table "page_partials".
 *
 * @todo Update properties
 * @property string $id
 * @property string $type
 * @property string $created_at
 * @property string $updated_at
 */
class PagePartial extends \yii\db\ActiveRecord
{
    const TYPE_SYSTEM = 'system';
    const TYPE_USER_DEFINED = 'user-defined';
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'page_partials';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // Required
            [['type', 'name'], 'required'],
            // Types
            [['type'], 'string'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'trim'],
            ['type', 'in', 'range' => ['system', 'user-defined']],
            [['created_at', 'updated_at'], 'integer'],
            // Default type to 'user-defined'
            ['type', 'default', 'value' => 'user-defined']           
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'type' => Yii::t('app', 'Type'),
            'name' => Yii::t('app', 'Name'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
    
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'translateable' => [
                'class' => TranslateableBehavior::className(),
                'translationAttributes' => ['title', 'content'],
            ],
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at',
                ],
                'value' => function() { return time(); },
            ],
            'image' => [
                'class' => 'infoweb\cms\behaviors\ImageBehave',
            ],
        ]);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTranslations()
    {
        return $this->hasMany(Lang::className(), ['page_partial_id' => 'id']);
    }
}
