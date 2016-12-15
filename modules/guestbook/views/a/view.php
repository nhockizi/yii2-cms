<?php
use yii\helpers\Html;
use nhockizi\cms\modules\guestbook\models\Guestbook;

$this->title = Yii::t('nhockizi_cms/guestbook', 'View post');
$this->registerCss('.guestbook-view dt{margin-bottom: 10px;}');
?>
<?= $this->render('_menu') ?>

<dl class="dl-horizontal guestbook-view">
    <dt><?= Yii::t('nhockizi_cms', 'Name') ?></dt>
    <dd><?= $model->name ?></dd>

    <?php if($this->context->module->settings['enableTitle']) : ?>
    <dt><?= Yii::t('nhockizi_cms', 'Title') ?></dt>
    <dd><?= $model->title ?></dd>
    <?php endif; ?>

    <?php if($this->context->module->settings['enableEmail']) : ?>
        <dt><?= Yii::t('nhockizi_cms', 'E-mail') ?></dt>
        <dd><?= $model->email ?></dd>
    <?php endif; ?>

    <dt>IP</dt>
    <dd><?= $model->ip ?> <a href="//freegeoip.net/?q=<?= $model->ip ?>" class="label label-info" target="_blank">info</a></dd>

    <dt><?= Yii::t('nhockizi_cms', 'Date') ?></dt>
    <dd><?= Yii::$app->formatter->asDatetime($model->time, 'medium') ?></dd>

    <dt><?= Yii::t('nhockizi_cms', 'Text') ?></dt>
    <dd><?= nl2br($model->text) ?></dd>
</dl>

<hr>
<h2><small><?= Yii::t('nhockizi_cms/guestbook', 'Answer') ?></small></h2>

<?= Html::beginForm() ?>
    <div class="form-group">
        <?= Html::textarea('Guestbook[answer]', $model->answer, ['class' => 'form-control', 'style' => 'height: 250px']) ?>
    </div>
    <?php if($model->answer == '' && $model->email) : ?>
    <div class="checkbox">
        <label>
            <input type="checkbox" name="mailUser" value="1" checked> <?= Yii::t('nhockizi_cms/guestbook', 'Notify user about answer') ?>
        </label>
    </div>
    <?php endif; ?>
    <?= Html::submitButton(Yii::t('nhockizi_cms', 'Save'), ['class' => 'btn btn-success send-answer']) ?>
<?= Html::endForm() ?>