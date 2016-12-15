<?php
namespace nhockizi\cms\modules\guestbook\controllers;

use Yii;
use yii\data\ActiveDataProvider;

use nhockizi\cms\behaviors\StatusController;
use nhockizi\cms\components\Controller;
use nhockizi\cms\modules\guestbook\models\Guestbook;

class AController extends Controller
{
    public $new = 0;
    public $noAnswer = 0;

    public function behaviors()
    {
        return [
            [
                'class' => StatusController::className(),
                'model' => Guestbook::className()
            ]
        ];
    }

    public function init()
    {
        parent::init();

        $this->new = Yii::$app->getModule('admin')->activeModules['guestbook']->notice;
        $this->noAnswer = Guestbook::find()->where(['answer' => ''])->count();
    }

    public function actionIndex()
    {
        $data = new ActiveDataProvider([
            'query' => Guestbook::find()->desc(),
        ]);

        return $this->render('index', [
            'data' => $data
        ]);
    }

    public function actionNoanswer()
    {
        $this->setReturnUrl();

        $data = new ActiveDataProvider([
            'query' => Guestbook::find()->where(['answer' => ''])->desc(),
        ]);
        return $this->render('index', [
            'data' => $data
        ]);
    }

    public function actionView($id)
    {
        $model = Guestbook::findOne($id);

        if($model === null){
            $this->flash('error', Yii::t('nhockizi_cms', 'Not found'));
            return $this->redirect(['/admin/'.$this->module->id]);
        }

        if($model->new > 0){
            $model->new = 0;
            $model->update();
        }

        if (Yii::$app->request->post('Guestbook')) {
            $model->answer = trim(Yii::$app->request->post('Guestbook')['answer']);
            if($model->save($model)){
                if(Yii::$app->request->post('mailUser')){
                    $model->notifyUser();
                }
                $this->flash('success', Yii::t('nhockizi_cms/guestbook', 'Answer successfully saved'));
            }
            else{
                $this->flash('error', Yii::t('nhockizi_cms', 'Update error. {0}', $model->formatErrors()));
            }
            return $this->refresh();
        }
        else {
            return $this->render('view', [
                'model' => $model
            ]);
        }
    }

    public function actionDelete($id)
    {
        if(($model = Guestbook::findOne($id))){
            $model->delete();
        } else {
            $this->error = Yii::t('nhockizi_cms', 'Not found');
        }
        return $this->formatResponse(Yii::t('nhockizi_cms/guestbook', 'Entry deleted'));
    }

    public function actionViewall()
    {
        Guestbook::updateAll(['new' => 0]);
        $module = \nhockizi\cms\models\Module::findOne(['name' => 'guestbook']);
        $module->notice = 0;
        $module->save();

        $this->flash('success', Yii::t('nhockizi_cms/guestbook', 'Guestbook updated'));

        return $this->back();
    }

    public function actionSetnew($id)
    {
        $model = Guestbook::findOne($id);

        if($model === null){
            $this->flash('error', Yii::t('nhockizi_cms', 'Not found'));
        }
        else{
            $model->new = 1;
            if($model->update()) {
                $this->flash('success', Yii::t('nhockizi_cms/guestbook', 'Guestbook updated'));
            }
            else{
                $this->flash('error', Yii::t('nhockizi_cms', 'Update error. {0}', $model->formatErrors()));
            }
        }
        return $this->redirect($this->getReturnUrl(['/admin/'.$this->module->id]));
    }

    public function actionOn($id)
    {
        return $this->changeStatus($id, Guestbook::STATUS_ON);
    }

    public function actionOff($id)
    {
        return $this->changeStatus($id, Guestbook::STATUS_OFF);
    }
}