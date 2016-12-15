<?php
namespace nhockizi\cms\modules\carousel\api;

use Yii;
use nhockizi\cms\components\API;
use nhockizi\cms\helpers\Data;
use nhockizi\cms\modules\carousel\models\Carousel as CarouselModel;
use yii\helpers\Html;
use yii\helpers\Url;

class Carousel extends API
{
    public $clientOptions = ['interval' => 5000];

    private $_items = [];

    public function init()
    {
        parent::init();

        $this->_items = Data::cache(CarouselModel::CACHE_KEY, 3600, function(){
            $items = [];
            foreach(CarouselModel::find()->status(CarouselModel::STATUS_ON)->sort()->all() as $item){
                $items[] = new CarouselObject($item);
            }
            return $items;
        });
    }

    public function api_widget($width, $height, $clientOptions = [])
    {
        if(!count($this->_items)){
            return LIVE_EDIT ? Html::a('Create carousel', ['/admin/carousel/a/create'], ['target' => '_blank']) : '';
        }
        if(count($clientOptions)){
            $this->clientOptions = array_merge($this->clientOptions, $clientOptions);
        }

        $items = [];
        foreach($this->_items as $item){
            $temp = [
                'content' => Html::img(Yii::$app->request->baseUrl.$item->thumb($width, $height)),
                'caption' => ''
            ];
            if($item->link) {
                $temp['content'] = Html::a($temp['content'], $item->link);
            }
            if($item->title){
                $temp['caption'] .= '<h3>' . $item->title . '</h3>';
            }
            if($item->text){
                $temp['caption'] .= '<p>'.$item->text.'</p>';
            }
            $items[] = $temp;
        }
        $widget = \yii\bootstrap\Carousel::widget([
            'options' => ['class' => 'slide'],
            'clientOptions' => $this->clientOptions,
            'items' => $items
        ]);

        return LIVE_EDIT ? API::liveEdit($widget, Url::to(['/admin/carousel']), 'div') : $widget;
    }

    public function api_items()
    {
        return $this->_items;
    }
}