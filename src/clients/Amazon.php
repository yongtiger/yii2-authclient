<?php ///[yongtiger/yii2-authclient]

/**
 * Yii2 user
 *
 * @link        http://www.brainbook.cc
 * @see         https://github.com/yongtiger/yii2-user
 * @author      Tiger Yong <tigeryang.brainbook@outlook.com>
 * @copyright   Copyright (c) 2016 BrainBook.CC
 * @license     http://opensource.org/licenses/MIT
 */

namespace yongtiger\authclient\clients;

use yii\authclient\OAuth2;
use yii\authclient\OAuthToken;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\HttpException;

/**
 * Amazon OAuth2
 *
 * In order to use Amazon OAuth2 you must register your application at <https://developer.yahoo.com>.
 *
 * Example application configuration:
 *
 * ```php
 * 'components' => [
 *     'authClientCollection' => [
 *         'class' => 'yii\authclient\Collection',
 *         'clients' => [
 *             'amazon' => [
 *                 'class' => 'yii\authclient\clients\Amazon',
 *                 'clientId' => 'amazon_client_id',
 *                 'clientSecret' => 'amazon_client_secret',
 *             ],
 *         ],
 *     ]
 *     // ...
 * ]
 * ```
 *
 * @see https://developer.yahoo.com
 * @see https://images-na.ssl-images-amazon.com/images/G/01/lwa/dev/docs/website-developer-guide._TTH_.pdf
 */
class Amazon extends OAuth2 implements IAuth
{
    public $authUrl = 'https://www.amazon.com/ap/oa';
    public $tokenUrl = 'https://api.amazon.com/auth/o2/token';
    public $apiBaseUrl = 'https://api.amazon.com';
    public $scope = 'profile';
    /**
     * Composes user authorization URL.
     * @param array $params additional auth GET params.
     * @return string authorization URL.
     */
    public function buildAuthUrl(array $params = [])
    {
        $defaultParams = [
            'client_id' => $this->clientId,
            'scope' => $this->scope,
            'response_type' => 'code',
            'redirect_uri' => $this->getReturnUrl(),
        ];
        if ($this->validateAuthState) {
            $authState = $this->generateAuthState();
            $this->setState('authState', $authState);
            $defaultParams['state'] = $authState;
        }
        return $this->composeUrl($this->authUrl, array_merge($defaultParams, $params));
    }
    /**
     * Fetches access token from authorization code.
     * @param string $authCode authorization code, usually comes at $_GET['code'].
     * @param array $params additional request params.
     * @return OAuthToken access token.
     * @throws HttpException on invalid auth state in case [[enableStateValidation]] is enabled.
     */
    public function fetchAccessToken($authCode, array $params = [])
    {
        if ($this->validateAuthState) {
            $authState = $this->getState('authState');
            if (!isset($_REQUEST['state']) || empty($authState) || strcmp($_REQUEST['state'], $authState) !== 0) {
                throw new HttpException(400, 'Invalid auth state parameter.');
            } else {
                $this->removeState('authState');
            }
        }
        $defaultParams = [
            'grant_type' => 'authorization_code',
            'code' => $authCode,
            'redirect_uri' => $this->getReturnUrl(),
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
        ];
        $request = $this->createRequest()
            ->setMethod('POST')
            ->addHeaders(['Content-Type' => 'application/x-www--urlencoded;charset=UTF-8'])
            ->setUrl($this->tokenUrl)
            ->setData(array_merge($defaultParams, $params));
        $response = $this->sendRequest($request);
        $token = $this->createToken(['params' => $response]);
        $this->setAccessToken($token);
        return $token;
    }
    /**
     * Clean ReturnUrl scope param
     * @return string
     */
    public function getReturnUrl()
    {
        $returnUrl = parent::getReturnUrl();
        $scope = "scope=" . urlencode($this->scope);
        $returnUrl = str_replace(["&{$scope}", "{$scope}&", "?{$scope}"], '', $returnUrl);
        return $returnUrl;
    }
    /**
     *
     * @return array
     * @see http://open.weibo.com/wiki/Oauth2/get_token_info
     * @see http://open.weibo.com/wiki/2/users/show
     */
    protected function initUserAttributes()
    {
        return $this->api('/auth/O2/tokeninfo');
    }
    /**
     * get UserInfo
     * @return array
     */
    public function getUserInfo()
    {
        return $this->api("/user/profile");
    }
    /**
     * @return string
     */
    public function getOpenid()
    {
        $attributes = $this->getUserAttributes();
        return ArrayHelper::getValue($attributes, 'user_id');
    }
    protected function defaultName()
    {
        return 'amazon';
    }
    protected function defaultTitle()
    {
        return 'Amazon';
    }
}