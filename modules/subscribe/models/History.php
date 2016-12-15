<?php
namespace nhockizi\cms\modules\subscribe\models;

use Yii;

class History extends \nhockizi\cms\components\ActiveRecord
{
    public static function tableName()
    {
        return '{{%subscribe_history}}';
    }

    public function rules()
    {
        return [
            [['subject', 'body'], 'required'],
            ['subject', 'trim'],
            ['sent', 'number', 'integerOnly' => true],
            ['time', 'default', 'value' => time()],
        ];
    }

    public function attributeLabels()
    {
        return [
            'subject' => Yii::t('nhockizi_cms/subscribe', 'Subject'),
            'body' => Yii::t('nhockizi_cms/subscribe', 'Body'),
        ];
    }
}