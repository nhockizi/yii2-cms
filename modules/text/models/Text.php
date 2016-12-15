<?php
namespace nhockizi\cms\modules\text\models;

use Yii;
use nhockizi\cms\behaviors\CacheFlush;

class Text extends \nhockizi\cms\components\ActiveRecord
{
    const CACHE_KEY = '{{%texts}}';

    public static function tableName()
    {
        return '{{%texts}}';
    }

    public function rules()
    {
        return [
            ['text_id', 'number', 'integerOnly' => true],
            ['text', 'required'],
            ['text', 'trim'],
            ['slug', 'match', 'pattern' => self::$SLUG_PATTERN, 'message' => 'Slug can contain only 0-9, a-z and "-" characters (max: 128).'],
            ['slug', 'default', 'value' => null],
            ['slug', 'unique']
        ];
    }

    public function attributeLabels()
    {
        return [
            'text' => Yii::t('nhockizi_cms', 'Text'),
            'slug' => Yii::t('nhockizi_cms', 'Slug'),
        ];
    }

    public function behaviors()
    {
        return [
            CacheFlush::className()
        ];
    }
}