<?php

namespace app\components\oauth2;

use League\OAuth2\Server\Entity\AuthCodeEntity;
use League\OAuth2\Server\Entity\ScopeEntity;
use League\OAuth2\Server\Storage\AbstractStorage;
use League\OAuth2\Server\Storage\AuthCodeInterface;
use yii\db\Query;
use yii\web\Session;
use Yii;

class AuthCodeStorage extends AbstractStorage implements AuthCodeInterface
{
    /**
     * {@inheritdoc}
     */
    public function get($code)
    {
        $query = new Query;
        $query->from('api.oauth_authorization_codes')
              ->where("authorization_code='". $code ."'")
              ->andWhere("expires >='".date('Y-m-d H:i:s',time())."'")
              ->select('*');

        $result = $query->createCommand()->queryAll();
        //var_dump($query->createCommand()->getRawSql());return;
        if (count($result) >= 1) {
            $token = new AuthCodeEntity($this->server);
            $token->setId($result[0]['authorization_code']);
            $token->setRedirectUri($result[0]['redirect_uri']);
            $token->setExpireTime(strtotime($result[0]['expires']));
            
            return $token;
        }

        return;
    }

    public function create($token, $expireTime, $sessionId, $redirectUri)
    {
        $session2 = new Session();
        $session2->open();
        
        $tokenModel = new \app\models\OAuth_Auth_Codes();
        
        $tokenModel->authorization_code = $token;
        $tokenModel->client_id = $session2['client_id'];
        $tokenModel->redirect_uri = $redirectUri;
        $tokenModel->session_id = $session2['oauth_session_id'];
        $tokenModel->user_id = $session2['user_id'];
        $tokenModel->expires = date('Y-m-d H:i:s',$expireTime);
        $tokenModel->save(FALSE);
        
        /*
        Capsule::table('oauth_auth_codes')
                    ->insert([
                        'auth_code'     =>  $token,
                        'client_redirect_uri'  =>  $redirectUri,
                        'session_id'    =>  $sessionId,
                        'expire_time'   =>  $expireTime,
                    ]);
         * 
         */
    }

    /**
     * {@inheritdoc}
     */
    public function getScopes(AuthCodeEntity $token)
    {
        /*$result = Capsule::table('oauth_auth_code_scopes')
                                    ->select(['oauth_scopes.id', 'oauth_scopes.description'])
                                    ->join('oauth_scopes', 'oauth_auth_code_scopes.scope', '=', 'oauth_scopes.id')
                                    ->where('auth_code', $token->getId())
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
        }*/

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function associateScope(AuthCodeEntity $token, ScopeEntity $scope)
    {
        Capsule::table('oauth_auth_code_scopes')
                    ->insert([
                        'auth_code' =>  $token->getId(),
                        'scope'     =>  $scope->getId(),
                    ]);
    }

    /**
     * {@inheritdoc}
     */
    public function delete(AuthCodeEntity $token){
        
        $authCodeModel = new \app\models\OAuth_Auth_Codes;
        $authCodeModel->deleteAll("authorization_code='".$token->getId()."'");
    }
}
