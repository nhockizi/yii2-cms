<?php
use nhockizi\cms\models\Setting;
use yii\helpers\Url;

$this->title = 'System';
?>

<h4>'Current version' : <b><?= Setting::get('nhockizi_cms_version') ?></b>
    <?php if(\nhockizi\cms\AdminModule::VERSION > floatval(Setting::get('nhockizi_cms_version'))) : ?>
        <a href="<?= Url::to(['/admin/system/update']) ?>" class="btn btn-success">Update</a>
    <?php endif; ?>
</h4>

<br>

<p>
    <a href="<?= Url::to(['/admin/system/flush-cache']) ?>" class="btn btn-default"><i class="glyphicon glyphicon-flash"></i>Flush cache</a>
</p>

<br>

<p>
    <a href="<?= Url::to(['/admin/system/clear-assets']) ?>" class="btn btn-default"><i class="glyphicon glyphicon-trash"></i>Clear assets</a>
</p>