<?php

namespace app\modules\v1;

class V1Module extends \yii\base\Module {

    public $controllerNamespace = 'app\modules\v1\controllers';

    public function init() {
        parent::init();

        // custom initialization code goes here
    }

    public function behaviors() {
        return [
            'corsFilter' => [
                'class' => \yii\filters\Cors::className(),
                'cors' => [
//                    // restrict access to
//                    'Origin' => ['http://www.myserver.com', 'https://www.myserver.com'],
//                    'Access-Control-Request-Method' => ['POST', 'PUT'],
//                    // Allow only POST and PUT methods
//                    'Access-Control-Request-Headers' => ['X-Wsse'],
//                    // Allow only headers 'X-Wsse'
//                    'Access-Control-Allow-Credentials' => true,
//                    // Allow OPTIONS caching
//                    'Access-Control-Max-Age' => 3600,
//                    // Allow the X-Pagination-Current-Page header to be exposed to the browser.
//                    'Access-Control-Expose-Headers' => ['X-Pagination-Current-Page'],
                    'Origin' => ['*']
                ],
            ],
        ];
    }

}
