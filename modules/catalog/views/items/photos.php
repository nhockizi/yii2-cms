<?php
use nhockizi\cms\widgets\Photos;

$this->title = 'Photos'. ' ' . $model->title;
?>

<?= $this->render('_menu', ['category' => $model->category]) ?>
<?= $this->render('_submenu', ['model' => $model]) ?>

<?= Photos::widget(['model' => $model])?>