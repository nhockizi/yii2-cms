<?php
namespace nhockizi\cms\modules\faq\api;

use Yii;
use nhockizi\cms\helpers\Data;
use nhockizi\cms\modules\faq\models\Faq as FaqModel;

class Faq extends \nhockizi\cms\components\API
{
    public function api_items()
    {
        return Data::cache(FaqModel::CACHE_KEY, 3600, function(){
            $items = [];
            foreach(FaqModel::find()->select(['faq_id', 'question', 'answer'])->status(FaqModel::STATUS_ON)->sort()->all() as $item){
                $items[] = new FaqObject($item);
            }
            return $items;
        });
    }
}