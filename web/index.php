<?php
header('Access-Control-Allow-Methods: DELETE,OPTIONS,PUT,POST');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization');
header('Access-Control-Allow-Origin: *');

require __DIR__ . '/../vendor/autoload.php';

$localhost = false;
if(array_key_exists('REMOTE_ADDR',$_SERVER))
{
    if($_SERVER['REMOTE_ADDR']=="127.0.0.1"
        || $_SERVER['REMOTE_ADDR']=="::1")
    {
        $localhost = true;
    }
}

$dotenv=new Symfony\Component\Dotenv\Dotenv();
$dotenv->load(__DIR__."/../.env");

// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', ($_ENV['YII_DEBUG']==="true" ? true : false));
defined('YII_ENV') or define('YII_ENV', $_ENV['YII_ENV']);

require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';
$config = require __DIR__ . '/../config/web.php';

(new yii\web\Application($config))->run();
