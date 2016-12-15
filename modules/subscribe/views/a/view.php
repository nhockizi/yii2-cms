<?php
$this->title = Yii::t('nhockizi_cms/subscribe', 'View subscribe history');
$this->registerCss('.subscribe-view dt{margin-bottom: 10px;}');
?>
<?= $this->render('_menu') ?>

<dl class="dl-horizontal subscribe-view">
    <dt><?= Yii::t('nhockizi_cms/subscribe', 'Subject') ?></dt>
    <dd><?= $model->subject ?></dd>

    <dt><?= Yii::t('nhockizi_cms', 'Date') ?></dt>
    <dd><?= Yii::$app->formatter->asDatetime($model->time, 'medium') ?></dd>

    <dt><?= Yii::t('nhockizi_cms/subscribe', 'Sent') ?></dt>
    <dd><?= $model->sent ?></dd>

    <dt><?= Yii::t('nhockizi_cms/subscribe', 'Body') ?></dt>
    <dd></dd>
</dl>
<?= $model->body ?>