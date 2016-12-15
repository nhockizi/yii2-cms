<?php
namespace nhockizi\cms\modules\subscribe\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

use nhockizi\cms\components\Controller;
use nhockizi\cms\models\Setting;
use nhockizi\cms\modules\subscribe\models\Subscriber;
use nhockizi\cms\modules\subscribe\models\History;

class AController extends Controller
{
    public function actionIndex()
    {
        $data = new ActiveDataProvider([
            'query' => Subscriber::find()->desc(),
        ]);
        return $this->render('index', [
            'data' => $data
        ]);
    }

    public function actionHistory()
    {
        $this->setReturnUrl();

        $data = new ActiveDataProvider([
            'query' => History::find()->desc(),
        ]);
        return $this->render('history', [
            'data' => $data
        ]);
    }

    public function actionView($id)
    {
        $model = History::findOne($id);

        if($model === null){
            $this->flash('error', Yii::t('nhockizi_cms', 'Not found'));
            return $this->redirect(['/admin/'.$this->module->id.'/history']);
        }

        return $this->render('view', [
            'model' => $model
        ]);
    }

    public function actionCreate()
    {
        $model = new History;

        if ($model->load(Yii::$app->request->post())) {
            if(Yii::$app->request->isAjax){
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            else
            {
                if($model->validate() && $this->send($model)){
                    $this->flash('success', Yii::t('nhockizi_cms/subscribe', 'Subscribe successfully created and sent'));
                    return $this->redirect(['/admin/'.$this->module->id.'/a/history']);
                }
                else{
                    $this->flash('error', Yii::t('nhockizi_cms', 'Create error. {0}', $model->formatErrors()));
                    return $this->refresh();
                }
            }
        }
        else {
            return $this->render('create', [
                'model' => $model
            ]);
        }
    }

    public function actionDelete($id)
    {
        if(($model = Subscriber::findOne($id))){
            $model->delete();
        } else {
            $this->error = Yii::t('nhockizi_cms', 'Not found');
        }
        return $this->formatResponse(Yii::t('nhockizi_cms/subscribe', 'Subscriber deleted'));
    }

    private function send($model)
    {
        $text = $model->body.
                "<br><br>".
                "--------------------------------------------------------------------------------";

        foreach(Subscriber::find()->all() as $subscriber){
			$unsubscribeLink = '<br><a href="' . Url::to(['/admin/'.$this->module->id.'/send/unsubscribe', 'email' => $subscriber->email], true) . '" target="_blank">'.Yii::t('nhockizi_cms/subscribe', 'Unsubscribe').'</a>';
            if(Yii::$app->mailer->compose()
                ->setFrom(Setting::get('robot_email'))
                ->setTo($subscriber->email)
                ->setSubject($model->subject)
                ->setHtmlBody($text.$unsubscribeLink)
                ->setReplyTo(Setting::get('admin_email'))
                ->send())
            {
                $model->sent++;
            }
        }

        return $model->save();
    }
}
