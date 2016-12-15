<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
?>
<?php $form = ActiveForm::begin(['action' => Url::to(['/admin/install'])]); ?>
<?= $form->field($model, 'root_password', ['inputOptions' => ['title' => 'Password to login as root']]) ?>
<?= $form->field($model, 'admin_email', ['inputOptions' => ['title' => 'Used as "ReplyTo" in mail messages']]) ?>
<?= $form->field($model, 'robot_email', ['inputOptions' => ['title' => 'Used as "From" in mail messages']]) ?>
<?= $form->field($model, 'recaptcha_key', ['inputOptions' => ['title' => 'Required for using captcha in forms (guestbook, feedback)']]) ?>
<?= $form->field($model, 'recaptcha_secret') ?>
<p class="recaptcha-tip"><?=  'You easily can get keys on' ?> <a href="https://www.google.com/recaptcha/intro/index.html" target="_blank"><?=  'ReCaptcha website' ?></a></p>
<?= Html::submitButton( 'Install', ['class' => 'btn btn-lg btn-primary btn-block']) ?>
<?php ActiveForm::end(); ?>