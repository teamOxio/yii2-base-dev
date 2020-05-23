<?php
$host = 'localhost';
$db='';
$username='';
$password='';

//for offline development
if(defined('YII_ENV'))
{
    if(YII_ENV == "dev")
    {
        $host = '127.0.0.1';
        $username = 'root';
        $db = 'base';
        $password = 'mysql';
    }
}
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host='.$host.';dbname='.$db,
    'username' => $username,
    'password' => $password,
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
