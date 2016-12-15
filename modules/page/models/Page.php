<?php
namespace nhockizi\cms\modules\page\models;

use Yii;
use nhockizi\cms\behaviors\SeoBehavior;

class Page extends \nhockizi\cms\components\ActiveRecord
{
    public static function tableName()
    {
        return '{{%pages}}';
    }

    public function rules()
    {
        return [
            ['title', 'required'],
            [['title', 'text'], 'trim'],
            ['title', 'string', 'max' => 128],
            ['slug', 'match', 'pattern' => self::$SLUG_PATTERN, 'message' => 'Slug can contain only 0-9, a-z and "-" characters (max: 128).'],
            ['slug', 'default', 'value' => null],
            ['slug', 'unique'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'title' => Yii::t('nhockizi_cms', 'Title'),
            'text' => Yii::t('nhockizi_cms', 'Text'),
            'slug' => Yii::t('nhockizi_cms', 'Slug'),
        ];
    }

    public function behaviors()
    {
        return [
            'seoBehavior' => SeoBehavior::className(),
        ];
    }
}