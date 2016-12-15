<?php
namespace nhockizi\cms\modules\subscribe\models;

use Yii;

class Subscriber extends \nhockizi\cms\components\ActiveRecord
{
    const FLASH_KEY = '{{%subscribe_subscribers}}';

    public static function tableName()
    {
        return '{{%subscribe_subscribers}}';
    }

    public function rules()
    {
        return [
            ['email', 'required'],
            ['email', 'trim'],
            ['email', 'email'],
            ['email', 'unique'],
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if($insert){
                $this->ip = Yii::$app->request->userIP;
                $this->time = time();
            }
            return true;
        } else {
            return false;
        }
    }

    public function attributeLabels()
    {
        return [
            'email' => 'E-mail',
        ];
    }
}