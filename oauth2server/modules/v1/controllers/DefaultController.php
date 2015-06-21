<?php

namespace app\modules\v1\controllers;

use yii\web\Controller;
use Yii;
use yii\web\Response;
use yii\helpers\Url;

class DefaultController extends Controller {

    public function actionIndex() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return array(
            "moreInfo" => "link to documentation here.",
            "resources" => array(
                //'datasets' => Url::to(['/v1/datasets'], true),
                'facilities' => Url::to(['/v1/facilities'], true),
                'phases' => Url::to(['/v1/phases'], true),
                'places' => Url::to(['/v1/places'], true),
                'programs' => Url::to(['/v1/programs'], true),
                'properties' => Url::to(['/v1/properties'], true),
                'scales' => Url::to(['/v1/scales'], true),
                'seasons' => Url::to(['/v1/seasons'], true),
                'studies' => Url::to(['/v1/studies'], true),
                'study-metadata' => Url::to(['/v1/study-metadata'], true),
                'transactions' => Url::to(['/v1/transactions'], true),
                'users' => Url::to(['/v1/users'], true),
//                'terminal-records' => Url::to(['/v1/terminal-records'], true),
                'variables' => Url::to(['/v1/variables'], true),
//                'scale-values' => Url::to(['/v1/scale-values'], true),
//                'records' => Url::to(['/v1/records'], true),
//                'record-variables' => Url::to(['/v1/record-variables'], true),
//                'items' => Url::to(['v1/items'], true),
//                'item-records' => Url::to(['v1/item-records'], true),
//                'item-relations' => Url::to(['v1/item-relations'], true),
        ));
    }

}
