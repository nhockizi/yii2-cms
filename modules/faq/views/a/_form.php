<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use nhockizi\cms\widgets\Redactor;
?>
<?php $form = ActiveForm::begin([
    'options' => ['class' => 'model-form']
]); ?>
<?= $form->field($model, 'question')->widget(Redactor::className(),[
    'options' => [
        'minHeight' => 300,
        'buttons' => ['bold', 'italic', 'unorderedlist', 'link'],
        'linebreaks' => true
    ]
]) ?>
<?= $form->field($model, 'answer')->widget(Redactor::className(),[
    'options' => [
        'minHeight' => 300,
        'buttons' => ['bold', 'italic', 'unorderedlist', 'link'],
        'linebreaks' => true
    ]
]) ?>

<?= Html::submitButton(Yii::t('nhockizi_cms','Save'), ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end(); ?>