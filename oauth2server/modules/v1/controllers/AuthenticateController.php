<?php

namespace app\modules\v1\controllers;

use Yii;
use yii\web\Controller;
use app\components\oauth2\SessionStorage;
use app\components\oauth2\AccessTokenStorage;
use app\components\oauth2\AuthCodeStorage;
use app\components\oauth2\ClientStorage;
use app\components\oauth2\ScopeStorage;
use \app\components\oauth2\RefreshTokenStorage;
use League\OAuth2\Server\AuthorizationServer;
use ChromePhp;
use yii\helpers\Url;
use yii\web\Session;
use linslin\yii2\curl;
use yii\db\Query;

class AuthenticateController extends Controller {

    public $modelClass = '';

    public $enableCsrfValidation = false;

    public function actionIndex() {

        $session = new Session();
        $session->open();

        // First ensure the parameters in the query string are correct
        try {
            $server = new AuthorizationServer;
            $server->setSessionStorage(new SessionStorage);
            $server->setAccessTokenStorage(new AccessTokenStorage);
            $server->setClientStorage(new ClientStorage);
            $server->setScopeStorage(new ScopeStorage);
            $server->setAuthCodeStorage(new AuthCodeStorage);

            $authCodeGrant = new \League\OAuth2\Server\Grant\AuthCodeGrant();
            $server->addGrantType($authCodeGrant);

            $authParams = $server->getGrantType('authorization_code')->checkAuthorizeParams();

            $session['client_id'] = $server->getRequest()->query->get('client_id', null);

            $session['authParams'] = json_encode($authParams);

            $this->redirect('authenticate/signin');
        } catch (Exception $e) {

            return json_encode('test');
        }
    }

    public function actionSignin() {
        $session = new Session();
        $session->open();

        $this->redirect('authorize');

    }

    function actionAuthorize() {

        $session = new Session();
        $session->open();

        $authParams = json_decode($session['authParams'], TRUE);

        if (!isset($_POST['authorization'])) {
            return $this->render('authorize', ['authParams' => $authParams, 'url' => Url::to(['authorize'])]);
        }

        if ($_POST['authorization'] === 'Approve') {
            $server = new AuthorizationServer;
            $server->setSessionStorage(new SessionStorage);
            $server->setAccessTokenStorage(new AccessTokenStorage);
            $server->setClientStorage(new ClientStorage);
            $server->setScopeStorage(new ScopeStorage);
            $server->setAuthCodeStorage(new AuthCodeStorage);

            $authCodeGrant = new \League\OAuth2\Server\Grant\AuthCodeGrant();
            $server->addGrantType($authCodeGrant);

            $clientEntity = new \League\OAuth2\Server\Entity\ClientEntity($server);

            $authParams['client'] = $clientEntity;

            $redirectUri = $server->getGrantType('authorization_code')->newAuthorizeRequest('user', 1, $authParams);

            /*
              $response = new Response('', 302, [
              'Location'  =>  $redirectUri
              ]);
             */

            $this->redirect($redirectUri);
        }
    }

    public function actionToken() {

        if ($_POST['grant_type'] === 'refresh_token') {
            $refreshTokenModel = new \app\models\OAuth_Refresh_Tokens;

            if(!isset($_POST['refresh_token'])){
                throw new \yii\web\HttpException(400, "Required parameter \'refresh_token\' is missing or invalid.");
            }

            $result = $refreshTokenModel->find()->where(['refresh_token' => trim($_POST['refresh_token'])])->one();
        }

        else if ($_POST['grant_type'] === 'authorization_code') {
            $authCodeModel = new \app\models\OAuth_Auth_Codes;

            if (!isset($_POST['code'])) {
                throw new \yii\web\HttpException(400, "Required parameter \'code\' is missing or invalid.");
            }

            $result = $authCodeModel->find()->where(['authorization_code' => trim($_POST['code'])])->one();

        }

        if (!empty($result)) {
                $user_id = $result->user_id;

                $session2 = new Session();
                $session2->open();

                $server = new AuthorizationServer;
                $server->setSessionStorage(new SessionStorage);
                $server->setAccessTokenStorage(new AccessTokenStorage);
                $server->setClientStorage(new ClientStorage);
                $server->setScopeStorage(new ScopeStorage);
                $server->setAuthCodeStorage(new AuthCodeStorage);
                $server->setRefreshTokenStorage(new RefreshTokenStorage);

                $refreshTokenGrant = new \League\OAuth2\Server\Grant\RefreshTokenGrant();
                $authCodeGrant = new \League\OAuth2\Server\Grant\AuthCodeGrant();

                $server->addGrantType($authCodeGrant);
                $server->addGrantType($refreshTokenGrant);
                $server->setAccessTokenTTL(5184000);
                $response = $server->issueAccessToken();

                $model = new \app\models\OAuth_Access_Tokens();
                $accessTokenModel = $model->find()->where(['access_token' => $response['access_token']])->one();
                $accessTokenModel->setAttribute('user_id', '' . $user_id);
                $accessTokenModel->save(FALSE);

                return json_encode($response);
            } else {
                throw new \yii\web\UnauthorizedHttpException("You have provided an invalid authorization code.");
            }
    }

}
