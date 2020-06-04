<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$dotenv=new Symfony\Component\Dotenv\Dotenv();
$dotenv->load(__DIR__."/../.env");

// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', ($_ENV['YII_DEBUG']==="true" ? true : false));
defined('YII_ENV') or define('YII_ENV', $_ENV['YII_ENV']);

$config = require __DIR__ . '/../config/web.php';

(new yii\web\Application($config))->run();
