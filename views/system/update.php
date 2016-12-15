<?php
use yii\helpers\Url;

$this->title = Yii::t('nhockizi_cms', 'Update');
?>
<ul class="nav nav-pills">
    <li>
        <a href="<?= Url::to(['/admin/system']) ?>">
            <i class="glyphicon glyphicon-chevron-left font-12"></i>
            <?= Yii::t('nhockizi_cms', 'Back') ?>
        </a>
    </li>
</ul>
<br>

<pre>
<?= $result ?>
</pre>
