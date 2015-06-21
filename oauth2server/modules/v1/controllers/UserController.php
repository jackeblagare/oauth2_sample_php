<?php

namespace app\modules\v1\controllers;

use Yii;
use yii\rest\ActiveController;
use app\modules\v1\models\ActiveDataProvider;
use yii\web\Link;
use yii\web\Linkable;
use yii\helpers\Url;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends BaseController implements Linkable {

    /**
     * @var string model class definition 
     */
    public $modelClass = 'app\models\User';

    /**
     * @var array configuration array for setting a pagination information to the response body and converting resource objects or collections into arrays. 
     */
    public $serializer = [
        'class' => 'app\modules\v1\models\Serializer2',
        'collectionEnvelope' => 'items',
    ];

    /**
     * generate link of the user view method
     * 
     * @return link of the user view
     */
    public function getLinks() {
        return [
            Link::REL_SELF => Url::to(['users/view', 'id' => $this->id], true),
        ];
    }

    /**
     * remove the default rest methods.
     * 
     * @return array array of methods
     */
    public function actions() {

        $actions = parent::actions();
        unset($actions['delete'], $actions['update'], $actions['deleteAll'], $actions['create'], $actions['index']);
        return $actions;
    }

    /**
     * Lists all season models
     * 
     * @return \yii\data\ActiveDataProvider dataprovider of the model.
     */
    public function actionIndex() {

        $modelClass = $this->modelClass;
        $user_id=Yii::$app->user->getId();
        $model = $modelClass::find()->where('is_void=false and id='.$user_id)->one();
       
        
        return $model;
    }

    /**
     * Lists all the team and its members
     * 
     * @return \yii\data\ActiveDataProvider dataprovider of the model.
     */
    public function actionTeams() {

        if ($id) {
            $modelClass = $this->modelClassChild;
            $model_study = $modelClass::findOne($id);
            // $model_study = $modelClass::find()->where(['variable.id=' => $id])->joinWith('scales')->one();
        }

        if (isset($model_study)) {
            $model = $modelClass::find();
            $model->joinWith('teams');
            $model->where(' master.user.is_void=false');

            $params = Yii::$app->getRequest()->getQueryParams();
            unset($params['id']);
            if (isset($params)) {
                $model = $modelClass::getQuery($params, $model);
            }

            $models = new ActiveDataProvider([
                'query' => $model,
            ]);

            return $models;
        } else {
            throw new NotFoundHttpException("Object not found: $id");
        }
    }

}
