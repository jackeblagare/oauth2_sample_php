<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "api.oauth_authorization_codes".
 *
 * @property string $authorization_code
 * @property string $user_id
 * @property string $redirect_uri
 * @property string $expires
 * @property string $scope
 * @property string $access_token
 * @property string $session_id
 */
class OAuth_Auth_Codes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'api.oauth_authorization_codes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['authorization_code', 'expires'], 'required'],
            [['expires'], 'safe'],
            [['authorization_code'], 'string', 'max' => 40],
            [['user_id', 'session_id'], 'string', 'max' => 255],
            [['redirect_uri', 'scope'], 'string', 'max' => 2000],
            [['access_token'], 'string', 'max' => 150]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'authorization_code' => 'Authorization Code',
            'user_id' => 'User ID',
            'redirect_uri' => 'Redirect Uri',
            'expires' => 'Expires',
            'scope' => 'Scope',
            'access_token' => 'Access Token',
            'session_id' => 'Session ID',
        ];
    }
}
