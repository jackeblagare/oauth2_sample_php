<?php

namespace app\modules\v1\controllers;

use yii\web\Linkable;
use yii\rest\ActiveController;

/**
 * Contains the configuration of behavaiours

 *
 * @author ncarumba
 */
class BaseController extends ActiveController {

    public function behaviors() {
//        $behaviors = parent::behaviors();
//        $behaviors['authenticator'] = [
//            'class' => \yii\filters\auth\QueryParamAuth::className(),
//            'tokenParam' => 'accessToken'
//        ];
        return \yii\helpers\ArrayHelper::merge(parent::behaviors(), [
                    'corsFilter' => [
                        'class' => \yii\filters\Cors::className(),
                        'cors' => [
                            // restrict access to
                            'Origin' => ['*'],
                            'Access-Control-Request-Method' => ['POST', 'PUT', 'GET', 'DELETE', 'OPTIONS'],
                            // Allow only POST and PUT methods
                            'Access-Control-Request-Headers' => ['*'],
                            // Allow only headers 'X-Wsse'
                            'Access-Control-Allow-Credentials' => true,
                            // Allow OPTIONS caching
                            'Access-Control-Max-Age' => 3600,
                            // Allow the X-Pagination-Current-Page header to be exposed to the browser.
                            'Access-Control-Expose-Headers' => ['X-Pagination-Current-Page'],
                        ],
                    ],
                    'verbs' => [
                        'class' => \yii\filters\VerbFilter::className(),
                        'actions' => [
                            'index' => ['get', 'options'],
                            'view' => ['get', 'options'],
                            'create' => ['options', 'get', 'post'],
                            'update' => ['get', 'put', 'post', 'options'],
                            'delete' => ['post', 'delete', 'options'],
                        ],
                    ],
                    'authenticator' => [
                        'class' => \yii\filters\auth\QueryParamAuth::className(),
                        'tokenParam' => 'accessToken'
                    ]
                        ]
        );
    }

    public function getLinks() {
        
    }

    public function actionResource() {
        return array(
            'resource' => $this->description(),
            'fields' => $this->fields(),
            'expand' => $this->expand(),
        );
    }

}
