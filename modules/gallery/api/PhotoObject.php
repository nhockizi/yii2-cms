<?php
namespace nhockizi\cms\modules\gallery\api;

use Yii;
use nhockizi\cms\components\API;
use yii\helpers\Html;
use yii\helpers\Url;

class PhotoObject extends \nhockizi\cms\components\ApiObject
{
    public $image;
    public $description;
    public $rel;

    public function box($width, $height){
        $img = Html::img(Yii::$app->request->baseUrl.$this->thumb($width, $height));
        $a = Html::a($img, Yii::$app->request->baseUrl.$this->image, [
            'class' => 'easyii-box',
            'rel' => 'album-' . ($this->rel ? $this->rel : $this->model->item_id),
            'title' => $this->description
        ]);
        return LIVE_EDIT ? API::liveEdit($a, $this->editLink) : $a;
    }

    public function getEditLink(){
        return Url::to(['/admin/gallery/a/photos', 'id' => $this->model->item_id]).'#photo-'.$this->id;
    }
}