<?php
namespace nhockizi\cms\controllers;

use yii\data\ActiveDataProvider;

use nhockizi\cms\models\LoginForm;

class LogsController extends \nhockizi\cms\components\Controller
{
    public $rootActions = 'all';

    public function actionIndex()
    {
        $data = new ActiveDataProvider([
            'query' => LoginForm::find()->desc(),
        ]);

        return $this->render('index', [
            'data' => $data
        ]);
    }
}