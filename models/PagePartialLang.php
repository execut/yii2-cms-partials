<?php

namespace infoweb\partials\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "page_partials_lang".
 *
 * @property string $page_partial_id
 * @property string $lang
 * @property string $name
 * @property string $content
 * @property string $created_at
 * @property string $updated_at
 */
class PagePartialLang extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'page_partials_lang';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // Required
            [['page_partial_id', 'language'], 'required'],
            // Only required for the app language
            [['name', 'content'], 'required', 'when' => function($model) {
                return $model->language == Yii::$app->language;
            }],
            // Trim
            [['name', 'content'], 'trim'],
            // Types
            [['page_partial_id', 'created_at', 'updated_at'], 'integer'],
            [['content'], 'string'],
            [['language'], 'string', 'max' => 2],
            [['name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'page_partial_id' => Yii::t('app', 'Page Partial ID'),
            'language' => Yii::t('app', 'Language'),
            'name' => Yii::t('app', 'Name'),
            'content' => Yii::t('app', 'Content'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
    
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
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
    public function getPagePartial()
    {
        return $this->hasOne(PagePartial::className(), ['id' => 'page_partial_id']);
    }
}
