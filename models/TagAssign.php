<?php
namespace nhockizi\cms\models;

class TagAssign extends \nhockizi\cms\components\ActiveRecord
{
    public static function tableName()
    {
    	return '{{%tags_assign}}';
    }
}