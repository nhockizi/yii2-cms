<?php
namespace nhockizi\cms\assets;

class EmptyAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@cms/media';
    public $css = [
        'css/empty.css',
    ];
    public $depends = [
        'yii\bootstrap\BootstrapAsset',
    ];
}
