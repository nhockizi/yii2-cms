<?php
namespace nhockizi\cms\modules\faq;

use Yii;

class FaqModule extends \nhockizi\cms\components\Module
{
    public static $installConfig = [
        'title' => [
            'en' => 'FAQ',
            'ru' => 'Вопросы и ответы',
        ],
        'icon' => 'question-sign',
        'order_num' => 45,
    ];
}