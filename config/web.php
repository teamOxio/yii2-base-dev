<?php

use sizeg\jwt\Jwt;
use sizeg\jwt\JwtValidationData;

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';
$queue = require __DIR__ . '/queue.php';
$common = require __DIR__ . '/common.php';

$config = [
    'id' => 'basic-app',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'modules' => [
        'v1' => [
            'class' => 'app\modules\api\ApiModule',
        ],
    ],
    'components' => [
        'queue'=>$queue,
        'view' => [
            'theme' => [
                'pathMap' => ['@app/views' => '@app/themes/backend/views'],
                'baseUrl' => '@web',
            ],
        ],
        'request' => [
            'cookieValidationKey' => 'dnmw4fGnucdxIB8DPVsjRzjSwrNGy4__',
        ],
        'jwt' => [
            'class' => Jwt::class,
            'key'   => '4fGnucdxIB8DPVdnmw4fGsjRzjSwrNGy4',
            'jwtValidationData' => JwtValidationData::class,
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\activerecord\Users',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                /* Uncomment for RESTful Active Controllers
                [
                    'class' => 'yii\rest\UrlRule',

                    'controller' => [

                    ],
//                    'pluralize'=>false,
                    'tokens' => [
                        '{id}' => '<id:([A-Za-z]+\.[\-a-zA-Z0-9\._]+)|(\d+)>',
                    ],
//                    'extraPatterns' => [
//                        'OPTIONS {action}' => 'options',
//                        'OPTIONS' => 'options'
//                    ],
                ],
                 */
            ],
        ],

    ],
    'params' => $params,
];

$config=array_merge($config,$common);

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
