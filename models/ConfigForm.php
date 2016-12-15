<?php
namespace nhockizi\cms\models;

use Yii;
use yii\base\Model;

class ConfigForm extends Model
{
    public $dbhost;
    public $dbname;
    public $username;
    public $password;
    public $prefix;

    public function rules()
    {
        return [
            ['dbhost', 'required'],
            ['dbhost', 'string', 'min' => 6],
            [['username', 'dbname','prefix'], 'string'],
            [['password'], 'trim'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'dbhost' => 'Database host',
            'dbname' => 'Database Name',
            'username' => 'Username',
            'password' => 'Password',
            'prefix' => 'Table Prefix',
        ];
    }
}