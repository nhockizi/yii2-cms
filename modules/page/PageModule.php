<?php
namespace nhockizi\cms\modules\page;

use Yii;

class PageModule extends \nhockizi\cms\components\Module
{
    public static $installConfig = [
        'title' => [
            'en' => 'Pages',
            'ru' => 'Страницы',
        ],
        'icon' => 'file',
        'order_num' => 50,
    ];
}