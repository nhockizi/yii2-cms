<?php
use yii\helpers\Url;

$asset = \nhockizi\cms\assets\EmptyAsset::register($this);;

$this->title = Yii::t('nhockizi_cms/install', 'Installation completed');
?>
<div class="container">
    <div id="wrapper" class="col-md-6 col-md-offset-3 vertical-align-parent">
        <div class="vertical-align-child">
            <div class="panel">
                <div class="panel-heading text-center">
                    <?= Yii::t('nhockizi_cms/install', 'Installation completed') ?>
                </div>
                <div class="panel-body text-center">
                    <a href="<?= Url::to(['/admin']) ?>">Go to control panel</a>
                </div>
            </div>
        </div>
    </div>
</div>
