<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
?>
<?php 
	$form = ActiveForm::begin(['action' => Url::to(['/admin/install/configdb'])]); 
	echo $form->field($model, 'dbhost', ['inputOptions' => ['value'=>'localhost']]);
	echo $form->field($model, 'dbname', ['inputOptions' => ['value'=>'nhockizi_cms']]);
	echo $form->field($model, 'username', ['inputOptions' => ['value'=>'username']]);
	echo $form->field($model, 'password', ['inputOptions' => ['value'=>'password']]);
	echo $form->field($model, 'prefix', ['inputOptions' => ['value'=>'nhockizi_']]);
	echo Html::submitButton('Install', ['class' => 'btn btn-lg btn-primary btn-block']);
	ActiveForm::end();
?>