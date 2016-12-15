<?php
namespace nhockizi\cms\assets;

class AdminAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@cms/media';
    public $css = [
        'css/admin.css',
    ];
    public $js = [
        'js/admin.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
        'nhockizi\cms\assets\SwitcherAsset',
    ];
    public $jsOptions = array(
        'position' => \yii\web\View::POS_HEAD
    );
}
