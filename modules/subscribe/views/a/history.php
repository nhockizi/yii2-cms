<?php
use yii\helpers\Url;

$this->title = Yii::t('nhockizi_cms/subscribe', 'History');

$module = $this->context->module->id;
?>

<?= $this->render('_menu') ?>

<?php if($data->count > 0) : ?>
    <table class="table table-hover">
        <thead>
            <tr>
                <?php if(IS_ROOT) : ?>
                    <th width="50">#</th>
                <?php endif; ?>
                <th><?= Yii::t('nhockizi_cms/subscribe', 'Subject') ?></th>
                <th width="150"><?= Yii::t('nhockizi_cms', 'Date') ?></th>
                <th width="120"><?= Yii::t('nhockizi_cms/subscribe', 'Sent') ?></th>
            </tr>
        </thead>
        <tbody>
    <?php foreach($data->models as $item) : ?>
            <tr>
                <?php if(IS_ROOT) : ?>
                    <td><?= $item->primaryKey ?></td>
                <?php endif; ?>
                <td><a href="<?= Url::to(['/admin/'.$module.'/a/view', 'id' => $item->primaryKey]) ?>"><?= $item->subject ?></a></td>
                <td><?= Yii::$app->formatter->asDatetime($item->time, 'short') ?></td>
                <td><?= $item->sent ?></td>
            </tr>
    <?php endforeach; ?>
        </tbody>
    </table>
    <?= yii\widgets\LinkPager::widget([
        'pagination' => $data->pagination
    ]) ?>
<?php else : ?>
    <p><?= Yii::t('nhockizi_cms', 'No records found') ?></p>
<?php endif; ?>