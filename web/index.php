<?php
$localhost = false;
if(array_key_exists('REMOTE_ADDR',$_SERVER))
{
    if($_SERVER['REMOTE_ADDR']=="127.0.0.1"
        || $_SERVER['REMOTE_ADDR']=="::1")
    {
        $localhost = true;
    }
}

// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', ($localhost ? true : false));
defined('YII_ENV') or define('YII_ENV', ($localhost ? 'dev' : 'prod'));

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/web.php';

(new yii\web\Application($config))->run();
