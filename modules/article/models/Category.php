<?php
namespace nhockizi\cms\modules\article\models;

class Category extends \nhockizi\cms\components\CategoryModel
{
    public static function tableName()
    {
        return '{{%article_categories}}';
    }

    public function getItems()
    {
        return $this->hasMany(Item::className(), ['category_id' => 'category_id'])->sortDate();
    }

    public function afterDelete()
    {
        parent::afterDelete();

        foreach ($this->getItems()->all() as $item) {
            $item->delete();
        }
    }
}