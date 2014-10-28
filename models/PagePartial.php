<?php

namespace infoweb\partials\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;
use dosamigos\translateable\TranslateableBehavior;

/**
 * This is the model class for table "page_partials".
 *
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
            [['type'], 'required'],
            // Types
            [['type'], 'string'],
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
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
    
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'trans' => [
                'class' => TranslateableBehavior::className(),
                'translationAttributes' => [
                    'name',
                    'content'
                ]
            ],
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at',
                ],
                'value' => function() { return time(); },
            ]
        ]);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTranslations()
    {
        return $this->hasMany(PagePartialLang::className(), ['page_partial_id' => 'id']);
    }
}
