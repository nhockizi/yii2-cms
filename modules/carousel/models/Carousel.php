<?php
namespace nhockizi\cms\modules\carousel\models;

use Yii;
use nhockizi\cms\behaviors\CacheFlush;
use nhockizi\cms\behaviors\SortableModel;

class Carousel extends \nhockizi\cms\components\ActiveRecord
{
    const STATUS_OFF = 0;
    const STATUS_ON = 1;
    const CACHE_KEY = '{{%carousel}}';

    public static function tableName()
    {
        return '{{%carousel}}';
    }

    public function rules()
    {
        return [
            ['image', 'image'],
            [['title', 'text', 'link'], 'trim'],
            ['status', 'integer'],
            ['status', 'default', 'value' => self::STATUS_ON],
        ];
    }

    public function attributeLabels()
    {
        return [
            'image' => Yii::t('nhockizi_cms', 'Image'),
            'link' =>  Yii::t('nhockizi_cms', 'Link'),
            'title' => Yii::t('nhockizi_cms', 'Title'),
            'text' => Yii::t('nhockizi_cms', 'Text'),
        ];
    }

    public function behaviors()
    {
        return [
            CacheFlush::className(),
            SortableModel::className()
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if(!$insert && $this->image != $this->oldAttributes['image'] && $this->oldAttributes['image']){
                @unlink(Yii::getAlias('@webroot').$this->oldAttributes['image']);
            }
            return true;
        } else {
            return false;
        }
    }

    public function afterDelete()
    {
        parent::afterDelete();

        @unlink(Yii::getAlias('@webroot').$this->image);
    }
}