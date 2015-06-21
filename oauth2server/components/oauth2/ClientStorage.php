<?php

namespace app\components\oauth2;

use Illuminate\Database\Capsule\Manager as Capsule;
use League\OAuth2\Server\Entity\ClientEntity;
use League\OAuth2\Server\Entity\SessionEntity;
use League\OAuth2\Server\Storage\AbstractStorage;
use League\OAuth2\Server\Storage\ClientInterface;
use yii\db\Query;

class ClientStorage extends AbstractStorage implements ClientInterface {

    /**
     * {@inheritdoc}
     */
    public function get($clientId, $clientSecret = null, $redirectUri = null, $grantType = null) {

        if (isset($clientId) && isset($redirectUri)) {

            $query = new Query;

            $query->from('api.oauth_clients')
                    ->where("client_id='" . $clientId . "'")
                    ->andWhere("redirect_uri='" . $redirectUri . "'")
                    ->select('*');
            
            try{
                $result = $query->createCommand()->queryAll();
            }
            catch(\yii\db\Exception $e){
                throw new \yii\db\Exception();
            }
            
            if (count($result) === 1) {
                $client = new ClientEntity($this->server);
                $client->hydrate([
                    'id' => $result[0]['client_id'],
                        //'name'  =>  $result[0]['name'],
                ]);

                return $client;
            }
        }

        return;
    }

    /**
     * {@inheritdoc}
     */
    public function getBySession(SessionEntity $session) {
        $result = Capsule::table('oauth_clients')
                ->select(['oauth_clients.id', 'oauth_clients.name'])
                ->join('oauth_sessions', 'oauth_clients.id', '=', 'oauth_sessions.client_id')
                ->where('oauth_sessions.id', $session->getId())
                ->get();

        if (count($result) === 1) {
            
            $client = new ClientEntity($this->server);
            $client->hydrate([
                'id' => $result[0]['id'],
                'name' => $result[0]['name'],
            ]);
            
            return $client;
        }

        return;
    }

}
