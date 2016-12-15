<?php
namespace nhockizi\cms\modules\shopcart\controllers;

use Yii;

use nhockizi\cms\components\Controller;
use nhockizi\cms\modules\shopcart\models\Good;

class GoodsController extends Controller
{
    public function actionDelete($id)
    {
        if(($model = Good::findOne($id))){
            $model->delete();
        } else {
            $this->error = Yii::t('nhockizi_cms', 'Not found');
        }
        return $this->formatResponse(Yii::t('nhockizi_cms/shopcart', 'Order deleted'));
    }
}