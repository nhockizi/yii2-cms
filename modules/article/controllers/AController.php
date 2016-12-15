<?php
namespace nhockizi\cms\modules\article\controllers;

use nhockizi\cms\components\CategoryController;

class AController extends CategoryController
{
    /** @var string  */
    public $categoryClass = 'nhockizi\cms\modules\article\models\Category';

    /** @var string  */
    public $moduleName = 'article';
}