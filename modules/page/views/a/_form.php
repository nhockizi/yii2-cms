<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use nhockizi\cms\widgets\Redactor;
use nhockizi\cms\widgets\SeoForm;
?>
<?php $form = ActiveForm::begin([
    'enableAjaxValidation' => true,
    'options' => ['class' => 'model-form']
]); ?>
<?= $form->field($model, 'title') ?>
<?= $form->field($model, 'text')->widget(Redactor::className(),[
    'options' => [
        'minHeight' => 400,
        'imageUpload' => Url::to(['/admin/redactor/upload', 'dir' => 'pages']),
        'fileUpload' => Url::to(['/admin/redactor/upload', 'dir' => 'pages']),
        'plugins' => ['fullscreen']
    ]
]) ?>

<?php if(IS_ROOT) : ?>
    <?= $form->field($model, 'slug') ?>
    <?= SeoForm::widget(['model' => $model]) ?>
<?php endif; ?>

<?= Html::submitButton(Yii::t('nhockizi_cms','Save'), ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end(); ?>