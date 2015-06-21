<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'modules' => [
        "v1" => [
            'class' => 'app\modules\v1\V1Module'   // here is our v1 modules
        ]
    ],
    'components' => [
        'session' => [
            'class' => 'yii\web\DbSession',
            'db' => 'db',
            'sessionTable' => 'public.session',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => false,
            'enableSession' => false,
            'loginUrl' => null
        ],
        'response' => [
            // Sets the application Response::$format

            'charset' => 'UTF-8',
            'class' => 'yii\web\Response',
            'on beforeSend' => function ($event) {
        $response = $event->sender;
        // print_r($response);
        $supressResponseParam = Yii::$app->request->get('suppress_response_code');
        if (is_array($response->data)) {
            if ($response->data !== null && !empty($supressResponseParam)) {
                $response->data = [
                    'success' => $response->isSuccessful,
                    'data' => $response->data,
                ];
                $response->statusCode = 200;
            } else {
                //print_r($response);
                if ((!$response->isSuccessful || $response->statusCode >= 400)) {
                    if (isset($response->data['name']) && isset($response->data['message'])) {
                        $response->data = [
                            'success' => $response->isSuccessful,
                            'data' => [
                                'name' => $response->data['name'],
                                'message' => $response->data['message'],
                                'status' => $response->statusCode,
                                'moreInfo' => ""
                            ],
                        ];
                    } else {

                        $response->data = [
                            'success' => $response->isSuccessful,
                            'data' => [
                                'name' => $response->statusText,
                                'message' => $response->data,
                                'status' => $response->statusCode,
                                'moreInfo' => ""
                            ],
                        ];
                    }
                } else {
                    $response->data = [
                        'success' => $response->isSuccessful,
                        'data' => $response->data
                    ];
                }
            }
            // $response->statusCode = 200;
        }
    },
        ],
        'oauth2' => [
            'class' => 'filsh\yii2\oauth2server\Module',
            'options' => [
                'token_param_name' => 'accessToken',
                'access_lifetime' => 3600 * 24
            ],
            'storageMap' => [
                'user_credentials' => 'common\models\User'
            ]
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'K2xP+le{!*c$nV~^NMRfg3N(88]?l>',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
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
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => true,
            'rules' => [
                'v1' => 'v1/default',
                '' => 'site',
                'v1/status' => 'v1/status',
                'v1/authenticate/token' => 'v1/authenticate/token',
                'v1/authenticate' => 'v1/authenticate/index',
                'v1/authenticate/authorize' => 'v1/authenticate/authorize',
                'v1/authenticate/signin' => 'v1/authenticate/signin',


            ],
        ],
        'db' => require(__DIR__ . '/db.php'),
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = 'yii\debug\Module';
}

return $config;
