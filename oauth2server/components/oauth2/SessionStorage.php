<?php

namespace app\components\oauth2;

use Illuminate\Database\Capsule\Manager as Capsule;
use League\OAuth2\Server\Entity\AccessTokenEntity;
use League\OAuth2\Server\Entity\AuthCodeEntity;
use League\OAuth2\Server\Entity\ScopeEntity;
use League\OAuth2\Server\Entity\SessionEntity;
use League\OAuth2\Server\Storage\AbstractStorage;
use League\OAuth2\Server\Storage\SessionInterface;
use yii\db\Query;
use app\models\OAuth_Sessions;
use yii\web\Session;

class SessionStorage extends AbstractStorage implements SessionInterface
{
    /**
     * {@inheritdoc}
     */
    public function getByAccessToken(AccessTokenEntity $accessToken)
    {
        $result = Capsule::table('oauth_sessions')
                            ->select(['oauth_sessions.id', 'oauth_sessions.owner_type', 'oauth_sessions.owner_id', 'oauth_sessions.client_id', 'oauth_sessions.client_redirect_uri'])
                            ->join('oauth_access_tokens', 'oauth_access_tokens.session_id', '=', 'oauth_sessions.id')
                            ->where('oauth_access_tokens.access_token', $accessToken->getId())
                            ->get();

        if (count($result) === 1) {
            $session = new SessionEntity($this->server);
            $session->setId($result[0]['id']);
            $session->setOwner($result[0]['owner_type'], $result[0]['owner_id']);

            return $session;
        }

        return;
    }

    /**
     * {@inheritdoc}
     */
    public function getByAuthCode(AuthCodeEntity $authCode)
    {
        $query = new Query;
        /*$sql = 'SELECT api.oauth_sessions.id,api.oauth_sessions.owner_type,api.oauth_sessions.owner_id,api.oauth_sessions.client_id'
                . 'FROM api.oauth_sessions INNER JOIN api.oauth_authorization_codes ON api.oauth_authoriation_codes.session_id = api.ouath_sessions.id '
                . 'WHERE api.oauth_authorization_codes.authoriation_code'*/
        $query->from('api.oauth_sessions')
              ->innerJoin('api.oauth_authorization_codes','api.oauth_authorization_codes.session_id = api.oauth_sessions.id')
              ->where("api.oauth_authorization_codes.authorization_code='". $authCode->getId() ."'")
              ->select(['api.oauth_sessions.id', 'api.oauth_sessions.owner_type', 'api.oauth_sessions.owner_id', 'oauth_sessions.client_id', 'oauth_authorization_codes.redirect_uri']);
        
        $result = $query->createCommand()->queryAll();
        
        if (count($result) === 1) {
            $session = new SessionEntity($this->server);
            $session->setId($result[0]['id']);
            $session->setOwner($result[0]['owner_type'], $result[0]['owner_id']);

            return $session;
        }

        return;
    }

    /**
     * {@inheritdoc}
     */
    public function getScopes(SessionEntity $session)
    {
        /*$result = Capsule::table('oauth_sessions')
                            ->select('oauth_scopes.*')
                            ->join('oauth_session_scopes', 'oauth_sessions.id', '=', 'oauth_session_scopes.session_id')
                            ->join('oauth_scopes', 'oauth_scopes.id', '=', 'oauth_session_scopes.scope')
                            ->where('oauth_sessions.id', $session->getId())
                            ->get();
        */                    
        $scopes = [];
/*
        foreach ($result as $scope) {
            $scopes[] = (new ScopeEntity($this->server))->hydrate([
                'id'            =>  $scope['id'],
                'description'   =>  $scope['description'],
            ]);
        }
*/
        return $scopes;
    }

    /**
     * {@inheritdoc}
     */
    public function create($ownerType, $ownerId, $clientId, $clientRedirectUri = null)
    {
        $query = new Query;
        
        $session = new OAuth_Sessions;
        
        $session->owner_type = $ownerType;
        $session->owner_id = $ownerId;
        $session->client_id = $clientId;
        
        $id = $session->save(FALSE);
        
        $session2 = new Session();
        $session2->open();
        
        $session2['oauth_session_id'] = $session->id;
        
        return $id;
    }

    /**
     * {@inheritdoc}
     */
    public function associateScope(SessionEntity $session, ScopeEntity $scope)
    {
        Capsule::table('oauth_session_scopes')
                            ->insert([
                                'session_id'    =>  $session->getId(),
                                'scope'         =>  $scope->getId(),
                            ]);
    }
}
