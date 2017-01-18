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

/**
 * Weixin OAuth2
 *
 * In order to use Weixin OAuth2 you must register your application at <https://developer.weixin.com>.
 *
 * Example application configuration:
 *
 * ```php
 * 'components' => [
 *     'authClientCollection' => [
 *         'class' => 'yii\authclient\Collection',
 *         'clients' => [
 *             'weixin' => [
 *                 'class' => 'yii\authclient\clients\Weixin',
 *                 'clientId' => 'weixin_client_id',
 *                 'clientSecret' => 'weixin_client_secret',
 *             ],
 *         ],
 *     ]
 *     // ...
 * ]
 * ```
 *
 * @see https://developer.weixin.com/
 */
class Weixin extends OAuth2 implements IAuth
{
    use ClientTrait;

    public $authUrl    = 'https://open.weixin.qq.com/connect/qrconnect';
    public $tokenUrl   = 'https://api.weixin.qq.com/sns/oauth2/access_token';
    public $apiBaseUrl = 'https://api.weixin.qq.com';
    public $scope      = 'snsapi_login';
    public $openid     = null;
    /**
     * Composes user authorization URL.
     *
     * @param array $params additional auth GET params.
     *
     * @return string authorization URL.
     */
    public function buildAuthUrl(array $params = [])
    {
        $defaultParams = [
            'appid'         => $this->clientId,
            'response_type' => 'code',
            'redirect_uri'  => $this->getReturnUrl(),
        ];
        if (!empty($this->scope)) {
            $defaultParams['scope'] = $this->scope;
        }
        return $this->composeUrl($this->authUrl, array_merge($defaultParams, $params));
    }
    /**
     * Fetches access token from authorization code.
     *
     * @param string $authCode authorization code, usually comes at $_GET['code'].
     * @param array  $params   additional request params.
     *
     * @return OAuthToken access token.
     */
    public function fetchAccessToken($authCode, array $params = [])
    {
        $defaultParams = [
            'appid'      => $this->clientId,
            'secret'     => $this->clientSecret,
            'code'       => $authCode,
            'grant_type' => 'authorization_code',
        ];
        $response = $this->sendRequest('POST', $this->tokenUrl, array_merge($defaultParams, $params));
        $this->openid = isset($response['openid']) ? $response['openid'] : null;
        $token = $this->createToken(['params' => $response]);
        $this->setAccessToken($token);
        return $token;
    }
    /**
     * @inheritdoc
     */
    protected function apiInternal($accessToken, $url, $method, array $params, array $headers)
    {
        $params['access_token'] = $accessToken->getToken();
        $params['openid'] = $this->openid;
        return $this->sendRequest($method, $url, $params, $headers);
    }
    /**
     * @return []
     * @see    https://open.weixin.qq.com/cgi-bin/showdocument?action=doc&id=open1419316518&t=0.14920092844688204
     */
    protected function initUserAttributes()
    {
        return $this->api('sns/userinfo');
    }
    /**
     * get UserInfo
     * @return []
     * @see    http://open.weibo.com/wiki/2/users/show
     */
    // public function getUserInfo()
    // {
    //     return $this->getUserAttributes();
    // }
    protected function defaultName()
    {
        return 'weixin';
    }
    protected function defaultTitle()
    {
        return '微信登陆';
    }
    protected function defaultViewOptions()
    {
        return [
            'popupWidth'  => 800,
            'popupHeight' => 500,
        ];
    }
}

// <?php
// namespace leap\oauth;
// use yii\authclient\OAuth2;
// use yii\web\HttpException;
// class Weixin extends OAuth2
// {
//     public $authUrl = 'https://open.weixin.qq.com/connect/qrconnect';
    
//     public $tokenUrl = 'https://api.weixin.qq.com/sns/oauth2/access_token';
    
//     public $apiBaseUrl = 'https://api.weixin.qq.com';
    
//     /**
//      * @inheritdoc
//      */
//     public function init()
//     {
//         parent::init();
//         if ($this->scope === null) {
//             $this->scope = 'snsapi_userinfo';
//         }
//     }
    
//     public function fetchAccessToken($authCode, array $params = [])
//     {
//         $defaultParams = [
//             'appid' => $this->clientId,
//             'secret' => $this->clientSecret,
//             'code' => $authCode,
//             'grant_type' => 'authorization_code',
//         ];
//         $request = $this->createRequest()
//             ->setMethod('POST')
//             ->setUrl($this->tokenUrl)
//             ->setData(array_merge($defaultParams, $params));
//         $response = $this->sendRequest($request);
//         $token = $this->createToken(['params' => $response]);
//         $this->setAccessToken($token);
//         return $token;
//     }
    
//     public function applyAccessTokenToRequest($request, $accessToken)
//     {
//         $data = $request->getData();
//         $data['access_token'] = $accessToken->getToken();
//         $data['openid'] = $accessToken->getParam('openid');
//         $request->setData($data);
//     }
    
//     public function initUserAttributes()
//     {
//         return $this->api('sns/userinfo', 'GET');
//     }
    
//     /**
//      * @inheritdoc
//      */
//     protected function defaultName()
//     {
//         return 'weixin';
//     }
//     /**
//      * @inheritdoc
//      */
//     protected function defaultTitle()
//     {
//         return 'Weixin';
//     }
// }

// <?php
// namespace lulubin\oauth;
// use Yii;
// use yii\authclient\OAuth2;
// use yii\authclient\OAuthToken;
// use yii\base\Exception;
// use yii\web\HttpException;
// /**
//  * Weixin OAuth
//  * @see https://open.weixin.qq.com/cgi-bin/showdocument?action=doc&id=open1419316505&t=0.1933593254077447
//  */
// class Weixin extends OAuth2
// {
//     public $authUrl = 'https://open.weixin.qq.com/connect/qrconnect';
//     public $tokenUrl = 'https://api.weixin.qq.com/sns/oauth2/access_token';
//     public $apiBaseUrl = 'https://api.weixin.qq.com';
//     public $scope = 'snsapi_login';
//     /**
//      * Composes user authorization URL.
//      * @param array $params additional auth GET params.
//      * @return string authorization URL.
//      */
//     public function buildAuthUrl(array $params = [])
//     {
//         $defaultParams = [
//             'appid' => $this->clientId,
//             'response_type' => 'code',
//             'redirect_uri' => $this->getReturnUrl(),
//         ];
//         if (!empty($this->scope)) {
//             $defaultParams['scope'] = $this->scope;
//         }
//         if ($this->validateAuthState) {
//             $authState = $this->generateAuthState();
//             $this->setState('authState', $authState);
//             $defaultParams['state'] = $authState;
//         }
//         return $this->composeUrl($this->authUrl, array_merge($defaultParams, $params));
//     }
//     /**
//      * Fetches access token from authorization code.
//      * @param string $authCode authorization code, usually comes at $_GET['code'].
//      * @param array $params additional request params.
//      * @return OAuthToken access token.
//      * @throws HttpException on invalid auth state in case [[enableStateValidation]] is enabled.
//      */
//     public function fetchAccessToken($authCode, array $params = [])
//     {
//         if ($this->validateAuthState) {
//             $authState = $this->getState('authState');
//             if (!isset($_REQUEST['state']) || empty($authState) || strcmp($_REQUEST['state'], $authState) !== 0) {
//                 throw new HttpException(400, 'Invalid auth state parameter.');
//             } else {
//                 $this->removeState('authState');
//             }
//         }
//         $defaultParams = [
//             'appid' => $this->clientId,
//             'secret' => $this->clientSecret,
//             'code' => $authCode,
//             'grant_type' => 'authorization_code',
//             'redirect_uri' => $this->getReturnUrl(),
//         ];
//         $request = $this->createRequest()
//             ->setMethod('POST')
//             ->setUrl($this->tokenUrl)
//             ->setData(array_merge($defaultParams, $params));
//         $response = $this->sendRequest($request);
//         $token = $this->createToken(['params' => $response]);
//         $this->setAccessToken($token);
//         return $token;
//     }
//     /**
//      * Handles [[Request::EVENT_BEFORE_SEND]] event.
//      * Applies [[accessToken]] to the request.
//      * @param \yii\httpclient\RequestEvent $event event instance.
//      * @throws Exception on invalid access token.
//      * @since 2.1
//      */
//     public function beforeApiRequestSend($event)
//     {
//         $request = $event->request;
//         $data = $request->getData();
//         $data['openid'] = $this->getOpenid();
//         $request->setData($data);
//         parent::beforeApiRequestSend($event);
//     }
//     /**
//      *
//      * @return []
//      * @see https://open.weixin.qq.com/cgi-bin/showdocument?action=doc&id=open1419316518&t=0.14920092844688204
//      */
//     protected function initUserAttributes()
//     {
//         return $this->api('sns/userinfo');
//     }
//     protected function defaultName()
//     {
//         return 'Weixin';
//     }
//     protected function defaultTitle()
//     {
//         return 'Weixin';
//     }
// }

// <?php
// namespace yujiandong\authclient;
// use yii\authclient\OAuth2;
// use yii\web\HttpException;
// use Yii;
// /**
//  * Weixin(Wechat) allows authentication via Weixin(Wechat) OAuth.
//  *
//  * In order to use Weixin(Wechat) OAuth you must register your application at <https://open.weixin.qq.com/> or <https://mp.weixin.qq.com/>.
//  *
//  * Example application configuration:
//  *
//  * ~~~
//  * 'components' => [
//  *     'authClientCollection' => [
//  *         'class' => 'yii\authclient\Collection',
//  *         'clients' => [
//  *             'weixin' => [   // for account of https://open.weixin.qq.com/
//  *                 'class' => 'yujiandong\authclient\Weixin',
//  *                 'clientId' => 'weixin_appid',
//  *                 'clientSecret' => 'weixin_appkey',
//  *             ],
//  *             'weixinmp' => [  // for account of https://mp.weixin.qq.com/
//  *                 'class' => 'yujiandong\authclient\Weixin',
//  *                 'type' => 'mp',
//  *                 'clientId' => 'weixin_appid',
//  *                 'clientSecret' => 'weixin_appkey',
//  *             ],
//  *         ],
//  *     ]
//  *     ...
//  * ]
//  * ~~~
//  *
//  * @see https://open.weixin.qq.com/
//  * @see https://open.weixin.qq.com/cgi-bin/showdocument?action=dir_list&t=resource/res_list&verify=1&lang=zh_CN
//  * @see https://mp.weixin.qq.com/
//  * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421140842&token=&lang=zh_CN
//  *
//  * @author Jiandong Yu <flyyjd@gmail.com>
//  * @since 2.0
//  */
// class Weixin extends OAuth2
// {
//     /**
//      * @inheritdoc
//      */
//     public $authUrl = 'https://open.weixin.qq.com/connect/qrconnect';
//     public $authUrlMp = 'https://open.weixin.qq.com/connect/oauth2/authorize';
//     /**
//      * @inheritdoc
//      */
//     public $tokenUrl = 'https://api.weixin.qq.com/sns/oauth2/access_token';
//     /**
//      * @inheritdoc
//      */
//     public $apiBaseUrl = 'https://api.weixin.qq.com';
//     public $type = null;
//     /**
//      * @inheritdoc
//      */
//     public function init()
//     {
//         parent::init();
//         if ($this->scope === null) {
//             $this->scope = implode(',', [
//                 'snsapi_userinfo',
//             ]);
//         }
//     }
//     /**
//      * @inheritdoc
//      */
//     protected function defaultNormalizeUserAttributeMap()
//     {
//         return [
//             'id' => 'openid',
//             'username' => 'nickname',
//         ];
//     }
//     /**
//      * @inheritdoc
//      */
//     public function buildAuthUrl(array $params = [])
//     {
//         $authState = $this->generateAuthState();
//         $this->setState('authState', $authState);
//         $defaultParams = [
//             'appid' => $this->clientId,
//             'redirect_uri' => $this->getReturnUrl(),
//             'response_type' => 'code',
//         ];
//         if (!empty($this->scope)) {
//             $defaultParams['scope'] = $this->scope;
//         }
//         $defaultParams['state'] = $authState;
//         $url = $this->type == 'mp'?$this->authUrlMp:$this->authUrl;
//         return $this->composeUrl($url, array_merge($defaultParams, $params));
//     }
//     /**
//      * @inheritdoc
//      */
//     public function fetchAccessToken($authCode, array $params = [])
//     {
//         $authState = $this->getState('authState');
//         if (!isset($_REQUEST['state']) || empty($authState) || strcmp($_REQUEST['state'], $authState) !== 0) {
//             throw new HttpException(400, 'Invalid auth state parameter.');
//         } else {
//             $this->removeState('authState');
//         }
//         $params['appid'] = $this->clientId;
//         $params['secret'] = $this->clientSecret;
//         return parent::fetchAccessToken($authCode, $params);
//     }
//     /**
//      * @inheritdoc
//      */
//     protected function apiInternal($accessToken, $url, $method, array $params, array $headers)
//     {
//         $params['access_token'] = $accessToken->getToken();
//         $params['openid'] = $accessToken->getParam('openid');
//         $params['lang'] = 'zh_CN';
//         return $this->sendRequest($method, $url, $params, $headers);
//     }
//     /**
//      * @inheritdoc
//      */
//     protected function initUserAttributes()
//     {
//         return $this->api('sns/userinfo');
// //        $userAttributes['id'] = $userAttributes['unionid'];
// //        return $userAttributes;
//     }
//     /**
//      * @inheritdoc
//      */
//     protected function defaultReturnUrl()
//     {
//         $params = $_GET;
//         unset($params['code']);
//         unset($params['state']);
//         $params[0] = Yii::$app->controller->getRoute();
//         return Yii::$app->getUrlManager()->createAbsoluteUrl($params);
//     }
//     /**
//      * Generates the auth state value.
//      * @return string auth state value.
//      */
//     protected function generateAuthState()
//     {
//         return sha1(uniqid(get_class($this), true));
//     }
//     /**
//      * @inheritdoc
//      */
//     protected function defaultName()
//     {
//         return 'weixin';
//     }
//     /**
//      * @inheritdoc
//      */
//     protected function defaultTitle()
//     {
//         return 'Weixin';
//     }
//     /**
//      * @inheritdoc
//      */
//     protected function defaultViewOptions()
//     {
//         return [
//             'popupWidth' => 800,
//             'popupHeight' => 500,
//         ];
//     }
// }

// <?php
// namespace lonelythinker\yii2\authclient\clients;
// use yii\authclient\OAuth2;
// use yii\web\HttpException;
// use Yii;
// /**
//  * Weixin(Wechat) allows authentication via Weixin(Wechat) OAuth.
//  *
//  * In order to use Weixin(Wechat) OAuth you must register your application at <https://open.weixin.qq.com/>.
//  *
//  * Example application configuration:
//  *
//  * ~~~
//  * 'components' => [
//  *     'authClientCollection' => [
//  *         'class' => 'yii\authclient\Collection',
//  *         'clients' => [
//  *             'weixin' => [   // for account of https://open.weixin.qq.com/
//  *                 'class' => 'lonelythinker\yii2\authclient\Weixin',
//  *                 'clientId' => 'weixin_appid',
//  *                 'clientSecret' => 'weixin_appkey',
//  *             ],
//  *             'weixinmp' => [  // for account of https://mp.weixin.qq.com/
//  *                 'class' => 'lonelythinker\yii2\authclient\Weixin',
//  *                 'type' => 'mp',
//  *                 'clientId' => 'weixin_appid',
//  *                 'clientSecret' => 'weixin_appkey',
//  *             ],
//  *         ],
//  *     ]
//  *     ...
//  * ]
//  * ~~~
//  *
//  * @see https://open.weixin.qq.com/
//  * @see https://open.weixin.qq.com/cgi-bin/showdocument?action=dir_list&t=resource/res_list&verify=1&lang=zh_CN
//  *
//  * @author : lonelythinker
//  * @email : 710366112@qq.com
//  * @homepage : www.lonelythinker.cn
//  */
// class Weixin extends OAuth2
// {
//     /**
//      * @inheritdoc
//      */
//     public $authUrl = 'https://open.weixin.qq.com/connect/qrconnect';
//     public $authUrlMp = 'https://open.weixin.qq.com/connect/oauth2/authorize';
//     /**
//      * @inheritdoc
//      */
//     public $tokenUrl = 'https://api.weixin.qq.com/sns/oauth2/access_token';
//     /**
//      * @inheritdoc
//      */
//     public $apiBaseUrl = 'https://api.weixin.qq.com';
//     public $type = null;
//     public $validateAuthState = false;
//     /**
//      * @inheritdoc
//      */
//     public function init()
//     {
//         parent::init();
//         if ($this->scope === null) {
//             //$this->scope = implode(',', $this->type == 'mp' ? ['snsapi_userinfo',] : ['snsapi_login']);
//             $this->scope = implode(',', ['snsapi_login']);
//         }
//     }
//     /**
//      * @inheritdoc
//      */
//     protected function defaultNormalizeUserAttributeMap()
//     {
//         return [
//             'id' => 'openid',
//             'username' => 'nickname',
//         ];
//     }
//     /**
//      * @inheritdoc
//      */
//     public function buildAuthUrl(array $params = [])
//     {
//         $authState = $this->generateAuthState();
//         $this->setState('authState', $authState);
//         $defaultParams = [
//             'appid' => $this->clientId,
//             'redirect_uri' => $this->getReturnUrl(),
//             'response_type' => 'code',
//         ];
//         if (!empty($this->scope)) {
//             $defaultParams['scope'] = $this->scope;
//         }
//         $defaultParams['state'] = $authState;
//         $url = $this->type == 'mp'?$this->authUrlMp:$this->authUrl;
//         return $this->composeUrl($url, array_merge($defaultParams, $params));
//     }
//     /**
//      * @inheritdoc
//      */
//     public function fetchAccessToken($authCode, array $params = [])
//     {
//         $authState = $this->getState('authState');
//         // if (!isset($_REQUEST['state']) || empty($authState) || strcmp($_REQUEST['state'], $authState) !== 0) {
//         //     throw new HttpException(400, 'Invalid auth state parameter.');
//         // } else {
//         //     $this->removeState('authState');
//         // }
//         $params['appid'] = $this->clientId;
//         $params['secret'] = $this->clientSecret;
//         return parent::fetchAccessToken($authCode, $params);
//     }
//     /**
//      * @inheritdoc
//      */
//     public function applyAccessTokenToRequest($request, $accessToken)
//     {
//         $data = $request->getData();
//         $data['access_token'] = $accessToken->getToken();
//         $data['openid'] = $accessToken->getParam('openid');
//         $data['lang'] = 'zh_CN';
//         $request->setData($data);
//     }
//     /**
//      * @inheritdoc
//      */
//     protected function initUserAttributes()
//     {
//         $userAttributes = $this->api('sns/userinfo');
        
// //        $userAttributes['id'] = $userAttributes['unionid'];
//         return $userAttributes;
//     }
//     /**
//      * @inheritdoc
//      */
//     protected function defaultReturnUrl()
//     {
//         $params = $_GET;
//         unset($params['code']);
//         unset($params['state']);
//         $params[0] = Yii::$app->controller->getRoute();
//         return Yii::$app->getUrlManager()->createAbsoluteUrl($params);
//     }
//     /**
//      * Generates the auth state value.
//      * @return string auth state value.
//      */
//     protected function generateAuthState()
//     {
//         return sha1(uniqid(get_class($this), true));
//     }
//     /**
//      * @inheritdoc
//      */
//     protected function defaultName()
//     {
//         return 'weixin';
//     }
//     /**
//      * @inheritdoc
//      */
//     protected function defaultTitle()
//     {
//         return 'Weixin';
//     }
//     /**
//      * @inheritdoc
//      */
//     protected function defaultViewOptions()
//     {
//         return [
//             'popupWidth' => 800,
//             'popupHeight' => 500,
//         ];
//     }
// }

// <?php
// namespace leap\oauth;
// use yii\authclient\OAuth2;
// use yii\web\HttpException;
// class Weixin extends OAuth2
// {
//     public $authUrl = 'https://open.weixin.qq.com/connect/qrconnect';
    
//     public $tokenUrl = 'https://api.weixin.qq.com/sns/oauth2/access_token';
    
//     public $apiBaseUrl = 'https://api.weixin.qq.com';
    
//     /**
//      * @inheritdoc
//      */
//     public function init()
//     {
//         parent::init();
//         if ($this->scope === null) {
//             $this->scope = 'snsapi_userinfo';
//         }
//     }
    
//     public function fetchAccessToken($authCode, array $params = [])
//     {
//         $defaultParams = [
//             'appid' => $this->clientId,
//             'secret' => $this->clientSecret,
//             'code' => $authCode,
//             'grant_type' => 'authorization_code',
//         ];
//         $request = $this->createRequest()
//             ->setMethod('POST')
//             ->setUrl($this->tokenUrl)
//             ->setData(array_merge($defaultParams, $params));
//         $response = $this->sendRequest($request);
//         $token = $this->createToken(['params' => $response]);
//         $this->setAccessToken($token);
//         return $token;
//     }
    
//     public function applyAccessTokenToRequest($request, $accessToken)
//     {
//         $data = $request->getData();
//         $data['access_token'] = $accessToken->getToken();
//         $data['openid'] = $accessToken->getParam('openid');
//         $request->setData($data);
//     }
    
//     public function initUserAttributes()
//     {
//         return $this->api('sns/userinfo', 'GET');
//     }
    
//     /**
//      * @inheritdoc
//      */
//     protected function defaultName()
//     {
//         return 'weixin';
//     }
//     /**
//      * @inheritdoc
//      */
//     protected function defaultTitle()
//     {
//         return 'Weixin';
//     }
// }

// <?php
// /**
//  * @link http://www.tintsoft.com/
//  * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
//  * @license http://www.tintsoft.com/license/
//  */
// namespace xutl\authclient;
// use Yii;
// use yii\base\Exception;
// use yii\authclient\OAuth2;
// /**
//  * Class Wechat
//  * @package xutl\authclient
//  */
// class Wechat extends OAuth2
// {
//     /**
//      * @inheritdoc
//      */
//     public $authUrl = 'https://open.weixin.qq.com/connect/qrconnect';
//     /**
//      * @inheritdoc
//      */
//     public $tokenUrl = 'https://api.weixin.qq.com/sns/oauth2/access_token';
//     /**
//      * @inheritdoc
//      */
//     public $apiBaseUrl = 'https://api.weixin.qq.com';
//     /**
//      * @inheritdoc
//      */
//     public function init()
//     {
//         parent::init();
//         if ($this->scope === null) {
//             $this->scope = implode(',', [
//                 'snsapi_login',
//             ]);
//         }
//     }
//     /**
//      * @inheritdoc
//      */
//     protected function defaultNormalizeUserAttributeMap()
//     {
//         return [
//             'id' => 'openid',
//             'username' => 'nickname',
//         ];
//     }
//     /**
//      * @inheritdoc
//      */
//     public function buildAuthUrl(array $params = [])
//     {
//         $params = array_merge($params, ['appid' => $this->clientId]);
//         return parent::buildAuthUrl($params);
//     }
//     /**
//      * @inheritdoc
//      */
//     public function fetchAccessToken($authCode, array $params = [])
//     {
//         $params = array_merge($params, ['appid' => $this->clientId, 'secret' => $this->clientSecret]);
//         return parent::fetchAccessToken($authCode, $params);
//     }
//     /**
//      * Handles [[Request::EVENT_BEFORE_SEND]] event.
//      * Applies [[accessToken]] to the request.
//      * @param \yii\httpclient\RequestEvent $event event instance.
//      * @throws Exception on invalid access token.
//      * @since 2.1
//      */
//     public function beforeApiRequestSend($event)
//     {
//         $event->request->addData(['openid' => $this->getOpenId()]);
//         parent::beforeApiRequestSend($event);
//     }
//     /**
//      * 返回OpenId
//      * @return mixed
//      */
//     public function getOpenId()
//     {
//         return $this->getAccessToken()->getParam('openid');
//     }
//     /**
//      * @inheritdoc
//      */
//     protected function initUserAttributes()
//     {
//         return $this->api('sns/userinfo', 'GET');
//     }
//     /**
//      * @inheritdoc
//      */
//     protected function defaultName()
//     {
//         return 'wechat';
//     }
//     /**
//      * @inheritdoc
//      */
//     protected function defaultTitle()
//     {
//         return Yii::t('app','Wechat');
//     }
//     /**
//      * @inheritdoc
//      */
//     protected function defaultViewOptions()
//     {
//         return [
//             'popupWidth' => 800,
//             'popupHeight' => 500,
//         ];
//     }
// }