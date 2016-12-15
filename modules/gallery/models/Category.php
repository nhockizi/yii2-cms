<?php
namespace nhockizi\cms\modules\gallery\models;

use nhockizi\cms\models\Photo;

class Category extends \nhockizi\cms\components\CategoryModel
{
    public static function tableName()
    {
        return '{{%gallery_categories}}';
    }

    public function getPhotos()
    {
        return $this->hasMany(Photo::className(), ['item_id' => 'category_id'])->where(['class' => self::className()])->sort();
    }

    public function afterDelete()
    {
        parent::afterDelete();

        foreach($this->getPhotos()->all() as $photo){
            $photo->delete();
        }
    }
}