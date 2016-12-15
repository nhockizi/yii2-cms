<?php
$asset = \nhockizi\cms\assets\EmptyAsset::register($this);

$this->title = 'Installation';
?>
<div class="container">
    <div id="wrapper" class="col-md-6 col-md-offset-3 vertical-align-parent">
        <div class="vertical-align-child">
            <div class="panel">
                <div class="panel-heading text-center">
                    Installation
                </div>
                <div class="panel-body">
                    <?= $this->render('_form', ['model' => $model])?>
                </div>
            </div>
        </div>
    </div>
</div>
