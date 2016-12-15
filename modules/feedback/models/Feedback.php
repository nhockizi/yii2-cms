<?php
namespace nhockizi\cms\modules\feedback\models;

use Yii;
use nhockizi\cms\behaviors\CalculateNotice;
use nhockizi\cms\helpers\Mail;
use nhockizi\cms\models\Setting;
use nhockizi\cms\validators\ReCaptchaValidator;
use nhockizi\cms\validators\EscapeValidator;
use yii\helpers\Url;

class Feedback extends \nhockizi\cms\components\ActiveRecord
{
    const STATUS_NEW = 0;
    const STATUS_VIEW = 1;
    const STATUS_ANSWERED = 2;

    const FLASH_KEY = '{{%feedback}}';

    public $reCaptcha;

    public static function tableName()
    {
        return '{{%feedback}}';
    }

    public function rules()
    {
        return [
            [['name', 'email', 'text'], 'required'],
            [['name', 'email', 'phone', 'title', 'text'], 'trim'],
            [['name','title', 'text'], EscapeValidator::className()],
            ['title', 'string', 'max' => 128],
            ['email', 'email'],
            ['phone', 'match', 'pattern' => '/^[\d\s-\+\(\)]+$/'],
            ['reCaptcha', ReCaptchaValidator::className(), 'when' => function($model){
                return $model->isNewRecord && Yii::$app->getModule('admin')->activeModules['feedback']->settings['enableCaptcha'];
            }],
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if($insert){
                $this->ip = Yii::$app->request->userIP;
                $this->time = time();
                $this->status = self::STATUS_NEW;
            }
            return true;
        } else {
            return false;
        }
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if($insert){
            $this->mailAdmin();
        }
    }

    public function attributeLabels()
    {
        return [
            'email' => 'E-mail',
            'name' => Yii::t('nhockizi_cms', 'Name'),
            'title' => Yii::t('nhockizi_cms', 'Title'),
            'text' => Yii::t('nhockizi_cms', 'Text'),
            'answer_subject' => Yii::t('nhockizi_cms/feedback', 'Subject'),
            'answer_text' => Yii::t('nhockizi_cms', 'Text'),
            'phone' => Yii::t('nhockizi_cms/feedback', 'Phone'),
            'reCaptcha' => Yii::t('nhockizi_cms', 'Anti-spam check')
        ];
    }

    public function behaviors()
    {
        return [
            'cn' => [
                'class' => CalculateNotice::className(),
                'callback' => function(){
                    return self::find()->status(self::STATUS_NEW)->count();
                }
            ]
        ];
    }

    public function mailAdmin()
    {
        $settings = Yii::$app->getModule('admin')->activeModules['feedback']->settings;

        if(!$settings['mailAdminOnNewFeedback']){
            return false;
        }
        return Mail::send(
            Setting::get('admin_email'),
            $settings['subjectOnNewFeedback'],
            $settings['templateOnNewFeedback'],
            ['feedback' => $this, 'link' => Url::to(['/admin/feedback/a/view', 'id' => $this->primaryKey], true)]
        );
    }

    public function sendAnswer()
    {
        $settings = Yii::$app->getModule('admin')->activeModules['feedback']->settings;

        return Mail::send(
            $this->email,
            $this->answer_subject,
            $settings['answerTemplate'],
            ['feedback' => $this],
            ['replyTo' => Setting::get('admin_email')]
        );
    }
}