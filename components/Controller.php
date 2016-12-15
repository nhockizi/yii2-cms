<?php
namespace nhockizi\cms\components;

use Yii;
use nhockizi\cms\models;
use yii\helpers\Url;

class Controller extends \yii\web\Controller
{
    public $enableCsrfValidation = false;
    public $rootActions = [];
    public $error = null;
    
    public function init()
    {
        parent::init();
        $this->layout = Yii::$app->getModule('admin')->controllerLayout;
    }

    public function beforeAction($action)
    {
        if(!parent::beforeAction($action))
            return false;

        if(Yii::$app->user->isGuest){
            Yii::$app->user->setReturnUrl(Yii::$app->request->url);
            Yii::$app->getResponse()->redirect(['/admin/sign/in'])->send();
            return false;
        }
        else{
            // if(!IS_ROOT && ($this->rootActions == 'all' || in_array($action->id, $this->rootActions))){
            //     throw new \yii\web\ForbiddenHttpException('You cannot access this action');
            // }

            if($action->id === 'index'){
                $this->setReturnUrl();
            }

            return true;
        }
    }

    public function flash($type, $message)
    {
        Yii::$app->getSession()->setFlash($type=='error'?'danger':$type, $message);
    }

    public function back()
    {
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function setReturnUrl($url = null)
    {
        Yii::$app->getSession()->set($this->module->id.'_return', $url ? Url::to($url) : Url::current());
    }

    public function getReturnUrl($defaultUrl = null)
    {
        return Yii::$app->getSession()->get($this->module->id.'_return', $defaultUrl ? Url::to($defaultUrl) : Url::to('/admin/'.$this->module->id));
    }

    public function formatResponse($success = '', $back = true)
    {
        if(Yii::$app->request->isAjax){
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            if($this->error){
                return ['result' => 'error', 'error' => $this->error];
            } else {
                $response = ['result' => 'success'];
                if($success) {
                    if(is_array($success)){
                        $response = array_merge(['result' => 'success'], $success);
                    } else {
                        $response = array_merge(['result' => 'success'], ['message' => $success]);
                    }
                }
                return $response;
            }
        }
        else{
            if($this->error){
                $this->flash('error', $this->error);
            } else {
                if(is_array($success) && isset($success['message'])){
                    $this->flash('success', $success['message']);
                }
                elseif(is_string($success)){
                    $this->flash('success', $success);
                }
            }
            return $back ? $this->back() : $this->refresh();
        }
    }
}
