<?php
namespace nhockizi\cms\modules\catalog\models;

use Yii;
use yii\behaviors\SluggableBehavior;
use nhockizi\cms\behaviors\SeoBehavior;
use nhockizi\cms\behaviors\SortableModel;
use nhockizi\cms\models\Photo;

class ItemData extends \nhockizi\cms\components\ActiveRecord
{

    public static function tableName()
    {
    	return '{{%catalog_item_data}}';
    }
}