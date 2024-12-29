<?php

//use kartik\mpdf\Pdf;

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' => 'uk-UA',
//    'defaultRoute' => 'site/index',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        // setup Krajee Pdf component
//        'pdf' => [
//            'class' => Pdf::classname(),
//            'format' => Pdf::FORMAT_A4,
//            'orientation' => Pdf::ORIENT_PORTRAIT,
//            'destination' => Pdf::DEST_BROWSER,
//            // refer settings section for all configuration options
//        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '6xEVKR4d5FgShb0a8H5iE-oAKuezl7KN',
            'baseUrl' => '',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
            'loginUrl' => ['site/login'],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
//        'pdf' => [
//            'class' => Pdf::classname(),
//            'format' => Pdf::FORMAT_A4,
//            'orientation' => Pdf::ORIENT_PORTRAIT,
//            'destination' => Pdf::DEST_BROWSER,
//            // refer settings section for all configuration options
//        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
            // send all mails to a file by default.
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
        'language' => 'uk-UA',
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '' => 'site/index',
                '<controller:\w+>/<action:\w+>/' => '<controller>/<action>',
            ],
        ],
        'assetManager' => [
            'bundles' => [
                'yii\web\BootstrapPluginAsset' => [
                    'js' => 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.min.js'
                ],
                'yii\web\JqueryAsset' => [
                    'js' => ['/js/jquery-3.5.1.min.js'],
//                    'js' => ['https://code.jquery.com/jquery-3.6.0.min.js'],
                    'jsOptions' => ['position' => \yii\web\View::POS_BEGIN],
                ],
            ],
            'linkAssets' => true,
        ],
    ],
    'params' => $params,
];

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
