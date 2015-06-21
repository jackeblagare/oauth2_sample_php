<?php

namespace app\models;

use Yii;
use yii\web\UnauthorizedHttpException;

/**
 * This is the model class for table "api.oauth_access_tokens".
 *
 * @property string $access_token
 * @property string $client_id
 * @property string $user_id
 * @property string $expires
 * @property string $scope
 */
class AccessTokens extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'api.oauth_access_tokens';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['access_token', 'client_id', 'expires'], 'required'],
            [['expires'], 'safe'],
            [['access_token'], 'string', 'max' => 40],
            [['client_id'], 'string', 'max' => 80],
            [['user_id'], 'string', 'max' => 255],
            [['scope'], 'string', 'max' => 2000]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'access_token' => 'Access Token',
            'client_id' => 'Client ID',
            'user_id' => 'User ID',
            'expires' => 'Expires',
            'scope' => 'Scope',
        ];
    }

    public function authenticate() {

        $accessToken = Yii::$app->getRequest()->getQueryParam('accessToken');
       // echo 'here: ' . $accessToken;
        if (is_string($accessToken) && !empty($accessToken)) {

            $identity = AccessTokens::findOne($accessToken);
            $user = User::findOne($identity->user_id);
            if ($identity !== null) {
                $identity->access_token = $accessToken;
                
                return $user;
            }
        } else {
            //$this->handleFailure($response);
            throw new UnauthorizedHttpException('You are requesting with an invalid access token.');
        }
        return null;
    }

}
