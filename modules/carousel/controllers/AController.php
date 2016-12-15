<?php
namespace nhockizi\cms\modules\carousel\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\widgets\ActiveForm;
use yii\web\UploadedFile;

use nhockizi\cms\components\Controller;
use nhockizi\cms\modules\carousel\models\Carousel;
use nhockizi\cms\helpers\Image;
use nhockizi\cms\behaviors\SortableController;
use nhockizi\cms\behaviors\StatusController;


class AController extends Controller
{
    public function behaviors()
    {
        return [
            [
                'class' => SortableController::className(),
                'model' => Carousel::className()
            ],
            [
                'class' => StatusController::className(),
                'model' => Carousel::className()
            ]
        ];
    }

    public function actionIndex()
    {
        $data = new ActiveDataProvider([
            'query' => Carousel::find()->sort(),
        ]);
        return $this->render('index', [
            'data' => $data
        ]);
    }

    public function actionCreate()
    {
        $model = new Carousel;

        if ($model->load(Yii::$app->request->post())) {
            if(Yii::$app->request->isAjax){
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            else{
                if(($fileInstanse = UploadedFile::getInstance($model, 'image')))
                {
                    $model->image = $fileInstanse;
                    if($model->validate(['image'])){
                        $model->image = Image::upload($model->image, 'carousel');
                        $model->status = Carousel::STATUS_ON;

                        if($model->save()){
                            $this->flash('success', Yii::t('nhockizi_cms/carousel', 'Carousel created'));
                            return $this->redirect(['/admin/'.$this->module->id]);
                        }
                        else{
                            $this->flash('error', Yii::t('nhockizi_cms', 'Create error. {0}', $model->formatErrors()));
                        }
                    }
                    else {
                        $this->flash('error', Yii::t('nhockizi_cms', 'Create error. {0}', $model->formatErrors()));
                    }
                }
                else {
                    $this->flash('error', Yii::t('yii', '{attribute} cannot be blank.', ['attribute' => $model->getAttributeLabel('image')]));
                }
                return $this->refresh();
            }
        }
        else {
            return $this->render('create', [
                'model' => $model
            ]);
        }
    }

    public function actionEdit($id)
    {
        $model = Carousel::findOne($id);

        if($model === null){
            $this->flash('error', Yii::t('nhockizi_cms', 'Not found'));
            return $this->redirect(['/admin/'.$this->module->id]);
        }

        if ($model->load(Yii::$app->request->post())) {
            if(Yii::$app->request->isAjax){
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            else{
                if(($fileInstanse = UploadedFile::getInstance($model, 'image')))
                {
                    $model->image = $fileInstanse;
                    if($model->validate(['image'])){
                        $model->image = Image::upload($model->image, 'carousel');
                    }
                    else {
                        $this->flash('error', Yii::t('nhockizi_cms', 'Update error. {0}', $model->formatErrors()));
                        return $this->refresh();
                    }
                }
                else{
                    $model->image = $model->oldAttributes['image'];
                }

                if($model->save()){
                    $this->flash('success', Yii::t('nhockizi_cms/carousel', 'Carousel updated'));
                }
                else{
                    $this->flash('error', Yii::t('nhockizi_cms/carousel','Update error. {0}', $model->formatErrors()));
                }
                return $this->refresh();
            }
        }
        else {
            return $this->render('edit', [
                'model' => $model
            ]);
        }
    }

    public function actionDelete($id)
    {
        if(($model = Carousel::findOne($id))){
            $model->delete();
        } else {
            $this->error = Yii::t('nhockizi_cms', 'Not found');
        }
        return $this->formatResponse(Yii::t('nhockizi_cms/carousel', 'Carousel item deleted'));
    }

    public function actionUp($id)
    {
        return $this->move($id, 'up');
    }

    public function actionDown($id)
    {
        return $this->move($id, 'down');
    }

    public function actionOn($id)
    {
        return $this->changeStatus($id, Carousel::STATUS_ON);
    }

    public function actionOff($id)
    {
        return $this->changeStatus($id, Carousel::STATUS_OFF);
    }
}