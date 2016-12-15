<?php
namespace nhockizi\cms\assets;

class PhotosAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@cms/assets/photos';
    public $css = [
        'photos.css',
    ];
    public $js = [
        'photos.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
