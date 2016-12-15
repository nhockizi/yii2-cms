<?php
namespace nhockizi\cms\controllers;

class DefaultController extends \nhockizi\cms\components\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}