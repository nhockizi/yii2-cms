<?php
namespace nhockizi\cms\modules\file\controllers;

use Yii;
use nhockizi\cms\modules\file\models\File;

class DownloadController extends \yii\web\Controller
{
    public function actionIndex($id)
    {
        $model = File::findOne($id);
        if($model){
            $model->updateCounters(['downloads' => 1]);
            Yii::$app->response->sendFile(Yii::getAlias('@webroot'). DIRECTORY_SEPARATOR .$model->file);
        }
        else{
            throw new \yii\web\NotFoundHttpException(Yii::t('nhockizi_cms/file/api', 'File not found'));
        }
    }
}
