<?php
namespace nhockizi\cms\controllers;

use Yii;
use nhockizi\cms\models\Tag;
use yii\helpers\Html;
use yii\web\Response;

class TagsController extends \nhockizi\cms\components\Controller
{
    public function actionList($query)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $items = [];
        $query = urldecode(mb_convert_encoding($query, "UTF-8"));
        foreach (Tag::find()->where(['like', 'name', $query])->asArray()->all() as $tag) {
            $items[] = ['name' => $tag['name']];
        }

        return $items;
    }
}