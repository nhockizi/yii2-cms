<?php
namespace nhockizi\cms\modules\gallery\controllers;

use nhockizi\cms\components\CategoryController;
use nhockizi\cms\modules\gallery\models\Category;

class AController extends CategoryController
{
    public $categoryClass = 'nhockizi\cms\modules\gallery\models\Category';
    public $moduleName = 'gallery';
    public $viewRoute = '/a/photos';

    public function actionPhotos($id)
    {
        if(!($model = Category::findOne($id))){
            return $this->redirect(['/admin/'.$this->module->id]);
        }

        return $this->render('photos', [
            'model' => $model,
        ]);
    }
}