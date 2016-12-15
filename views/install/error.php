<?php
$asset = \nhockizi\cms\assets\EmptyAsset::register($this);

$this->title = 'Installation error';
?>
<div class="container">
    <div id="wrapper" class="col-md-6 col-md-offset-3 vertical-align-parent">
        <div class="vertical-align-child">
            <div class="panel">
                <div class="panel-heading text-center">
                    Installation error
                </div>
                <div class="panel-body text-center">
                    <?= $error ?>
                </div>
            </div>
        </div>
    </div>
</div>
