<?php

namespace app\components\oauth2;

use Illuminate\Database\Capsule\Manager as Capsule;
use League\OAuth2\Server\Entity\RefreshTokenEntity;
use League\OAuth2\Server\Storage\AbstractStorage;
use League\OAuth2\Server\Storage\RefreshTokenInterface;
use yii\db\Query;

class RefreshTokenStorage extends AbstractStorage implements RefreshTokenInterface
{
    /**
     * {@inheritdoc}
     */
    public function get($token)
    {
        $query = new Query;
        
        $query->from('api.oauth_refresh_tokens')
                    ->where("refresh_token='" . $token . "'")
                    ->select('*');

        $result = $query->createCommand()->queryAll();

        if (count($result) === 1) {
             $token = (new RefreshTokenEntity($this->server))
                    ->setId($result[0]['refresh_token'])
                    ->setExpireTime($result[0]['expires']);
                 
            return $token;
        }
     
        return;
    }

    /**
     * {@inheritdoc}
     */
    public function create($token, $expireTime, $accessToken)
    {
        $session2 = new \yii\web\Session;
        $session2->open();
        
        $model = new \app\models\OAuth_Refresh_Tokens;

        $model->refresh_token = trim($token);
        $model->access_token = trim($accessToken);
        $model->client_id = trim('');
        $model->user_id = $session2['user_id'];
        $model->expires = date('Y-m-d H:i:s',$expireTime);

        $model->save(FALSE);
    }

    /**
     * {@inheritdoc}
     */
    public function delete(RefreshTokenEntity $token)
    {
        $refreshTokenModel = new \app\models\OAuth_Refresh_Tokens;
        $refreshTokenModel->deleteAll("refresh_token='".$token->getId()."'");
    }
}