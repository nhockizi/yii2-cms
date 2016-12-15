<?php
namespace nhockizi\cms\components;

use Yii;

class API extends \yii\base\Object
{
    static $classes;
    public $module;

    public function init()
    {
        parent::init();

        $this->module = Module::getModuleName(self::className());
        Module::registerTranslations($this->module);
    }

    public static function __callStatic($method, $params)
    {
        $name = (new \ReflectionClass(self::className()))->getShortName();
        if (!isset(self::$classes[$name])) {
            self::$classes[$name] = new static();
        }
        return call_user_func_array([self::$classes[$name], 'api_' . $method], $params);
    }

    public static  function liveEdit($text, $path, $tag = 'span')
    {
        return $text ? '<'.$tag.' class="nhockizi-cms-edit" data-edit="'.$path.'">'.$text.'</'.$tag.'>' : '';
    }
}