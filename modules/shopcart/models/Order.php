<?php
namespace nhockizi\cms\modules\shopcart\models;

use Yii;
use nhockizi\cms\behaviors\CalculateNotice;
use nhockizi\cms\helpers\Mail;
use nhockizi\cms\models\Setting;
use nhockizi\cms\validators\EscapeValidator;
use yii\helpers\Url;

class Order extends \nhockizi\cms\components\ActiveRecord
{
    const STATUS_BLANK = 0;
    const STATUS_PENDING = 1;
    const STATUS_PROCESSED = 2;
    const STATUS_DECLINED = 3;
    const STATUS_SENT = 4;
    const STATUS_RETURNED = 5;
    const STATUS_ERROR = 6;
    const STATUS_COMPLETED = 7;

    const SESSION_KEY = '{{%shopcart_orders}}';

    public static function tableName()
    {
        return '{{%shopcart_orders}}';
    }

    public function rules()
    {
        return [
            [['name', 'address'], 'required', 'on' => 'confirm'],
            ['email', 'required', 'when' => function($model){ return $model->scenario == 'confirm' && Yii::$app->getModule('admin')->activeModules['shopcart']->settings['enableEmail']; }],
            ['phone', 'required', 'when' => function($model){ return $model->scenario == 'confirm' && Yii::$app->getModule('admin')->activeModules['shopcart']->settings['enablePhone']; }],
            [['name', 'address', 'phone', 'comment'], 'trim'],
            ['email', 'email'],
            ['name', 'string', 'max' => 32],
            ['address', 'string', 'max' => 1024],
            ['phone', 'string', 'max' => 32],
            ['phone', 'match', 'pattern' => '/^[\d\s-\+\(\)]+$/'],
            ['comment', 'string', 'max' => 1024],
            [['name', 'address', 'phone', 'comment'], EscapeValidator::className()],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => Yii::t('nhockizi_cms', 'Name'),
            'email' => Yii::t('nhockizi_cms', 'E-mail'),
            'address' => Yii::t('nhockizi_cms/shopcart', 'Address'),
            'phone' => Yii::t('nhockizi_cms/shopcart', 'Phone'),
            'comment' => Yii::t('nhockizi_cms/shopcart', 'Comment'),
            'remark' => Yii::t('nhockizi_cms/shopcart', 'Admin remark'),
        ];
    }

    public function behaviors()
    {
        return [
            'cn' => [
                'class' => CalculateNotice::className(),
                'callback' => function(){
                    return self::find()->where(['new' => 1])->count();
                }
            ]
        ];
    }

    public static function statusName($status)
    {
        $states = self::states();
        return !empty($states[$status]) ? $states[$status] : $status;
    }

    public static function states()
    {
        return [
            self::STATUS_BLANK => Yii::t('nhockizi_cms/shopcart', 'Blank'),
            self::STATUS_PENDING => Yii::t('nhockizi_cms/shopcart', 'Pending'),
            self::STATUS_PROCESSED => Yii::t('nhockizi_cms/shopcart', 'Processed'),
            self::STATUS_DECLINED => Yii::t('nhockizi_cms/shopcart', 'Declined'),
            self::STATUS_SENT => Yii::t('nhockizi_cms/shopcart', 'Sent'),
            self::STATUS_RETURNED => Yii::t('nhockizi_cms/shopcart', 'Returned'),
            self::STATUS_ERROR => Yii::t('nhockizi_cms/shopcart', 'Error'),
            self::STATUS_COMPLETED => Yii::t('nhockizi_cms/shopcart', 'Completed'),
        ];
    }

    public function getStatusName()
    {
        $states = self::states();
        return !empty($states[$this->status]) ? $states[$this->status] : $this->status;
    }

    public function getGoods()
    {
        return $this->hasMany(Good::className(), ['order_id' => 'order_id']);
    }

    public function getCost()
    {
        $total = 0;
        foreach($this->goods as $good){
            $total += $good->count * round($good->price * (1 - $good->discount / 100));
        }

        return $total;
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if($insert) {
                $this->ip = Yii::$app->request->userIP;
                $this->access_token = Yii::$app->security->generateRandomString(32);
                $this->time = time();
            } else {
                if($this->oldAttributes['status'] == self::STATUS_BLANK && $this->status == self::STATUS_PENDING){
                    $this->new = 1;
                    $this->mailAdmin();
                }
            }
            return true;
        } else {
            return false;
        }
    }

    public function afterDelete()
    {
        parent::afterDelete();

        foreach($this->getGoods()->all() as $good){
            $good->delete();
        }
    }

    public function mailAdmin()
    {
        $settings = Yii::$app->getModule('admin')->activeModules['shopcart']->settings;

        if(!$settings['mailAdminOnNewOrder']){
            return false;
        }
        return Mail::send(
            Setting::get('admin_email'),
            $settings['subjectOnNewOrder'],
            $settings['templateOnNewOrder'],
            [
                'order' => $this,
                'link' => Url::to(['/admin/shopcart/a/view', 'id' => $this->primaryKey], true)
            ]
        );
    }

    public function notifyUser()
    {
        $settings = Yii::$app->getModule('admin')->activeModules['shopcart']->settings;

        return Mail::send(
            $this->email,
            $settings['subjectNotifyUser'],
            $settings['templateNotifyUser'],
            [
                'order' => $this,
                'link' => Url::to([$settings['frontendShopcartRoute'], 'id' => $this->primaryKey, 'token' => $this->access_token], true)
            ]
        );
    }
}