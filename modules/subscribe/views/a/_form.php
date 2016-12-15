<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use nhockizi\cms\widgets\Redactor;
?>
<?php $form = ActiveForm::begin([
    'enableClientValidation' => true
]); ?>
<?= $form->field($model, 'subject') ?>
<?= $form->field($model, 'body')->widget(Redactor::className(),[
    'options' => [
        'minHeight' => 400,
    ]
]) ?>
<?= Html::submitButton(Yii::t('nhockizi_cms', 'Send'), ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end(); ?>