<?php
namespace nhockizi\cms\modules\faq\models;

use Yii;
use nhockizi\cms\behaviors\CacheFlush;
use nhockizi\cms\behaviors\SortableModel;

class Faq extends \nhockizi\cms\components\ActiveRecord
{
    const STATUS_OFF = 0;
    const STATUS_ON = 1;

    const CACHE_KEY = '{{%faq}}';

    public static function tableName()
    {
        return '{{%faq}}';
    }

    public function rules()
    {
        return [
            [['question','answer'], 'required'],
            [['question', 'answer'], 'trim'],
            ['status', 'integer'],
            ['status', 'default', 'value' => self::STATUS_ON],
        ];
    }

    public function attributeLabels()
    {
        return [
            'question' => Yii::t('nhockizi_cms/faq', 'Question'),
            'answer' => Yii::t('nhockizi_cms/faq', 'Answer'),
        ];
    }

    public function behaviors()
    {
        return [
            CacheFlush::className(),
            SortableModel::className()
        ];
    }
}