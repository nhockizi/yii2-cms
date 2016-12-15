<?php
namespace nhockizi\cms\modules\subscribe;

class SubscribeModule extends \nhockizi\cms\components\Module
{
    public static $installConfig = [
        'title' => [
            'en' => 'E-mail subscribe',
            'ru' => 'E-mail рассылка',
        ],
        'icon' => 'envelope',
        'order_num' => 10,
    ];
}