<?php

namespace app\components\oauth2;

use app\models\OAuth_Access_Tokens;
use League\OAuth2\Server\Entity\AccessTokenEntity;
use League\OAuth2\Server\Entity\ScopeEntity;
use League\OAuth2\Server\Storage\AbstractStorage;
use League\OAuth2\Server\Storage\AccessTokenInterface;
use ChromePhp;
use yii\db\Query;
use yii\web\Session;
use Yii;

class AccessTokenStorage extends AbstractStorage implements AccessTokenInterface
{
    /**
     * {@inheritdoc}
     */
    public function get($token){
        
        $query = new Query;
        $query->from('api.oauth_access_tokens')
              ->where("access_token='". $token ."'")
              ->select('*');

        $result = $query->createCommand()->queryAll();
        
        if (count($result) === 1) {
            
            $token = (new AccessTokenEntity($this->server))
                        ->setId($result[0]['access_token'])
                        ->setExpireTime($result[0]['expires']);

            return $token;
        }

        return;
    }

    /**
     * {@inheritdoc}
     */
    public function getScopes(AccessTokenEntity $token)
    {
        /*$result = Capsule::table('oauth_access_token_scopes')
                                    ->select(['oauth_scopes.id', 'oauth_scopes.description'])
                                    ->join('oauth_scopes', 'oauth_access_token_scopes.scope', '=', 'oauth_scopes.id')
                                    ->where('access_token', $token->getId())
                                    ->get();
        */                            
        $response = [];
/*
        if (count($result) > 0) {
            foreach ($result as $row) {
                $scope = (new ScopeEntity($this->server))->hydrate([
                    'id'            =>  $row['id'],
                    'description'   =>  $row['description'],
                ]);
                $response[] = $scope;
            }
        }
*/
        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function create($token, $expireTime, $sessionId)
    {
        $session2 = new Session();
        $session2->open();
        
        $model = new OAuth_Access_Tokens;

        $model->access_token = trim($token);
        $model->client_id = trim('');
        $model->user_id = $session2['user_id'];
        $model->expires = date('Y-m-d H:i:s',$expireTime);

        $model->save(FALSE);

    }

    /**
     * {@inheritdoc}
     */
    public function associateScope(AccessTokenEntity $token, ScopeEntity $scope)
    {
        /*Capsule::table('oauth_access_token_scopes')
                    ->insert([
                        'access_token'  =>  $token->getId(),
                        'scope' =>  $scope->getId(),
                    ]);*/
    }

    /**
     * {@inheritdoc}
     */
    public function delete(AccessTokenEntity $token)
    {
        Capsule::table('oauth_access_token_scopes')
                    ->where('access_token', $token->getId())
                    ->delete();
    }
}
