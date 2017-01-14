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
use yii\base\Exception;
use yii\helpers\Json;

/**
 * QQ OAuth2
 *
 * In order to use QQ OAuth2 you must register your application at <https://connect.qq.com/>.
 *
 * Example application configuration:
 *
 * ```php
 * 'components' => [
 *     'authClientCollection' => [
 *         'class' => 'yii\authclient\Collection',
 *         'clients' => [
 *             'qq' => [
 *                 'class' => 'yii\authclient\clients\Qq',
 *                 'clientId' => 'qq_client_id',
 *                 'clientSecret' => 'qq_client_secret',
 *             ],
 *         ],
 *     ]
 *     // ...
 * ]
 * ```
 *
 * @see http://open.qq.com/
 * @see https://connect.qq.com/
 */
class Qq extends OAuth2 implements IAuth
{
    public $authUrl = 'https://graph.qq.com/oauth2.0/authorize';
    public $tokenUrl = 'https://graph.qq.com/oauth2.0/token';
    public $apiBaseUrl = 'https://graph.qq.com';
    public function init()
    {
        parent::init();
        if ($this->scope === null) {
            $this->scope = implode(',', [
                'get_user_info',
            ]);
        }
    }
    protected function initUserAttributes()
    {
        $openid =  $this->api('oauth2.0/me', 'GET');
        $qquser = $this->api("user/get_user_info", 'GET', ['oauth_consumer_key'=>$openid['client_id'], 'openid'=>$openid['openid']]);
        $qquser['openid'] = $openid['openid'];
        $qquser['id'] = $qquser['openid'];
        $qquser['login'] = $qquser['nickname'];
        $qquser['email'] = $qquser['nickname'] . '@qq.com';
        return $qquser;
    }
    protected function defaultName()
    {
        return 'qq';
    }
    protected function defaultTitle()
    {
        return 'Qq';
    }
    
    
    /**
     * 该扩展初始的处理方法似乎QQ互联不能用，应此改写了方法
     * @see \yii\authclient\BaseOAuth::processResponse()
     */
    protected function processResponse($rawResponse, $contentType = self::CONTENT_TYPE_AUTO)
    {
        if (empty($rawResponse)) {
            return [];
        }
        switch ($contentType) {
            case self::CONTENT_TYPE_AUTO: {
                $contentType = $this->determineContentTypeByRaw($rawResponse);
                if ($contentType == self::CONTENT_TYPE_AUTO) {
                    //以下代码是特别针对QQ互联登录的，也是与原方法不一样的地方 
                    if(strpos($rawResponse, "callback") !== false){
                        $lpos = strpos($rawResponse, "(");
                        $rpos = strrpos($rawResponse, ")");
                        $rawResponse = substr($rawResponse, $lpos + 1, $rpos - $lpos -1);
                        $response = $this->processResponse($rawResponse, self::CONTENT_TYPE_JSON);
                        break;
                    }
                    //代码添加结束
                    throw new Exception('Unable to determine response content type automatically.');
                }
                $response = $this->processResponse($rawResponse, $contentType);
                break;
            }
            case self::CONTENT_TYPE_JSON: {
                $response = Json::decode($rawResponse, true);
                if (isset($response['error'])) {
                    throw new Exception('Response error: ' . $response['error']);
                }
                break;
            }
            case self::CONTENT_TYPE_URLENCODED: {
                $response = [];
                parse_str($rawResponse, $response);
                break;
            }
            case self::CONTENT_TYPE_XML: {
                $response = $this->convertXmlToArray($rawResponse);
                break;
            }
            default: {
                throw new Exception('Unknown response type "' . $contentType . '".');
            }
        }
       
        return $response;
    }
    
    
}

// <?php
// /**
//  * AuthInterface.php
//  * @auther: yuyangame<kangzhq@foxmail.com>
//  */
// namespace yuyangame\oauth;
// use yii\authclient\OAuth2;
// class QqAuth extends OAuth2 implements AuthInterface
// {
//     public $authUrl    = 'https://graph.qq.com/oauth2.0/authorize';
//     public $tokenUrl   = 'https://graph.qq.com/oauth2.0/token';
//     public $apiBaseUrl = 'https://graph.qq.com';
//     public function init()
//     {
//         parent::init();
//         if ($this->scope === null) {
//             $this->scope = implode(',', [
//                 'get_user_info',
//             ]);
//         }
//     }
//     protected function initUserAttributes()
//     {
//         return $this->api('oauth2.0/me', 'GET');
//     }
//     /**
//      * get UserInfo
//      * @return []
//      * @see    http://wiki.connect.qq.com/get_user_info
//      */
//     public function getUserInfo()
//     {
//         $openid = $this->getUserAttributes();
//         return $this->api("user/get_user_info", 'GET', [
//             'oauth_consumer_key' => $openid['client_id'],
//             'openid'             => $openid['openid']]);
//     }
//     protected function defaultName()
//     {
//         return 'qq';
//     }
//     protected function defaultTitle()
//     {
//         return 'QQ登陆';
//     }
//     protected function defaultViewOptions()
//     {
//         return [
//             'popupWidth'  => 800,
//             'popupHeight' => 500,
//         ];
//     }
//     /**
//      * Processes raw response converting it to actual data.
//      *
//      * @param string $rawResponse raw response.
//      * @param string $contentType response content type.
//      *
//      * @throws \yii\base\Exception on failure.
//      * @return array actual response.
//      */
//     protected function processResponse($rawResponse, $contentType = self::CONTENT_TYPE_AUTO)
//     {
//         if ($contentType == self::CONTENT_TYPE_AUTO) {
//             //jsonp to json
//             if (strpos($rawResponse, "callback") === 0) {
//                 $lpos = strpos($rawResponse, "(");
//                 $rpos = strrpos($rawResponse, ")");
//                 $rawResponse = substr($rawResponse, $lpos + 1, $rpos - $lpos - 1);
//                 $rawResponse = trim($rawResponse);
//                 $contentType = self::CONTENT_TYPE_JSON;
//             }
//         }
//         return parent::processResponse($rawResponse, $contentType);
//     }
// }


// <?php
// namespace lulubin\oauth;
// use yii\authclient\InvalidResponseException;
// use yii\authclient\OAuth2;
// use yii\httpclient\Request;
// use yii\httpclient\Response;
// class Qq extends OAuth2
// {
//     public $authUrl = 'https://graph.qq.com/oauth2.0/authorize';
//     public $tokenUrl = 'https://graph.qq.com/oauth2.0/token';
//     public $apiBaseUrl = 'https://graph.qq.com';
//     public function init()
//     {
//         parent::init();
//         if ($this->scope === null) {
//             $this->scope = implode(',', [
//                 'get_user_info',
//             ]);
//         }
//     }
//     protected function initUserAttributes()
//     {
//         $openid =  $this->api('oauth2.0/me', 'GET');
//         $qquser = $this->api("user/get_user_info", 'GET', ['oauth_consumer_key'=>$openid['client_id'], 'openid'=>$openid['openid']]);
//         $qquser['openid']=$openid['openid'];
//         $qquser['id']=$qquser['openid'];
//         $qquser['username']=$qquser['nickname'];
//         return $qquser;
//     }
//     protected function defaultName()
//     {
//         return 'QQ';
//     }
//     protected function defaultTitle()
//     {
//         return 'QQ';
//     }
//     /**
//      * Sends the given HTTP request, returning response data.
//      * @param \yii\httpclient\Request $request HTTP request to be sent.
//      * @return array response data.
//      * @throws InvalidResponseException on invalid remote response.
//      * @since 2.1
//      */
//     protected function sendRequest($request)
//     {
//         $response = $request->send();
//         if (!$response->getIsOk()) {
//             throw new InvalidResponseException($response, 'Request failed with code: ' . $response->getStatusCode() . ', message: ' . $response->getContent());
//         }
//         $this->processResult($response);
//         return $response->getData();
//     }
//     /**
//      * @param Response $response
//      */
//     protected function processResult(Response $response)
//     {
//         $content = $response->getContent();
//         if (strpos($content, "callback") !== 0) {
//             return;
//         }
//         $lpos = strpos($content, "(");
//         $rpos = strrpos($content, ")");
//         $content = substr($content, $lpos + 1, $rpos - $lpos - 1);
//         $content = trim($content);
//         $response->setContent($content);
//     }
// }

// <?php

// namespace frontend\widgets;

// use yii\authclient\OAuth2;
// use yii\web\HttpException;
// use Yii;

// class QQClient extends OAuth2
// {
//     public $authUrl = 'https://graph.qq.com/oauth2.0/authorize';

//     public $tokenUrl = 'https://graph.qq.com/oauth2.0/token';

//     public $apiBaseUrl = 'https://graph.qq.com';


//     protected function initUserAttributes()
//     {
//         $user = $this->api('user/get_user_info', 'GET', ['oauth_consumer_key' => $this->user->client_id, 'openid' => $this->user->openid]);

//         return [
//             'client' => 'qq',
//             'openid' => $this->user->openid,
//             'nickname' => $user['nickname'],
//             'gender' => $user['gender'],
//             'location' => $user['province'] . $user['city'],
//         ];
//     }

//     /**
//      * @inheritdoc
//      */
//     protected function getUser()
//     {
//         $str = file_get_contents('https://graph.qq.com/oauth2.0/me?access_token=' . $this->accessToken->token);

//         if (strpos($str, "callback") !== false) {
//             $lpos = strpos($str, "(");
//             $rpos = strrpos($str, ")");
//             $str = substr($str, $lpos + 1, $rpos - $lpos -1);
//         }

//         return json_decode($str);
//     }

//     /**
//      * @inheritdoc
//      */
//     protected function defaultName()
//     {
//         return 'QQ';
//     }

//     /**
//      * @inheritdoc
//      */
//     protected function defaultTitle()
//     {
//         return 'QQ 登录';
//     }
// }

// <?php
// namespace yujiandong\authclient;
// use yii\authclient\OAuth2;
// use yii\web\HttpException;
// use Yii;
// /**
//  * QQ allows authentication via QQ OAuth.
//  *
//  * In order to use QQ OAuth you must register your application at <http://connect.qq.com/>.
//  *
//  * Example application configuration:
//  *
//  * ~~~
//  * 'components' => [
//  *     'authClientCollection' => [
//  *         'class' => 'yii\authclient\Collection',
//  *         'clients' => [
//  *             'qq' => [
//  *                 'class' => 'yujiandong\authclient\Qq',
//  *                 'clientId' => 'qq_appid',
//  *                 'clientSecret' => 'qq_appkey',
//  *             ],
//  *         ],
//  *     ]
//  *     ...
//  * ]
//  * ~~~
//  *
//  * @see http://connect.qq.com/
//  * @see http://wiki.connect.qq.com/
//  *
//  * @author Jiandong Yu <flyyjd@gmail.com>
//  */
// class Qq extends OAuth2
// {
//     /**
//      * @inheritdoc
//      */
//     public $authUrl = 'https://graph.qq.com/oauth2.0/authorize';
//     /**
//      * @inheritdoc
//      */
//     public $tokenUrl = 'https://graph.qq.com/oauth2.0/token';
//     /**
//      * @inheritdoc
//      */
//     public $apiBaseUrl = 'https://graph.qq.com';
//     /**
//      * @inheritdoc
//      */
//     public function init()
//     {
//         parent::init();
//         if ($this->scope === null) {
//             $this->scope = implode(' ', [
//                 'get_user_info',
//             ]);
//         }
//     }
//     /**
//      * @inheritdoc
//      */
//     public function buildAuthUrl(array $params = [])
//     {
//         $authState = $this->generateAuthState();
//         $this->setState('authState', $authState);
//         $params['state'] = $authState;
//         return parent::buildAuthUrl($params);
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
//         return parent::fetchAccessToken($authCode, $params);
//     }
//     /**
//      * @inheritdoc
//      */
//     protected function defaultNormalizeUserAttributeMap()
//     {
//         return [
//             'username' => 'nickname',
//         ];
//     }
//     /**
//      * @inheritdoc
//      */
//     protected function initUserAttributes()
//     {
//         $user = $this->api('oauth2.0/me', 'GET');
//         if ( isset($user['error']) ) {
//             throw new HttpException(400, $user['error']. ':'. $user['error_description']);
//         }
//         $userAttributes = $this->api(
//             "user/get_user_info",
//             'GET',
//             [
//                 'oauth_consumer_key' => $user['client_id'],
//                 'openid' => $user['openid'],
//             ]
//         );
//         $userAttributes['id'] = $user['openid'];
//         return $userAttributes;
//     }
//     /**
//      * @inheritdoc
//      */
//     protected function processResponse($rawResponse, $contentType = self::CONTENT_TYPE_AUTO)
//     {
//         if ($contentType === self::CONTENT_TYPE_AUTO && strpos($rawResponse, "callback(") === 0) {
//             $count = 0;
//             $jsonData = preg_replace('/^callback\(\s*(\\{.*\\})\s*\);$/is', '\1', $rawResponse, 1, $count);
//             if ($count === 1) {
//                 $rawResponse = $jsonData;
//                 $contentType = self::CONTENT_TYPE_JSON;
//             }
//         }
//         return parent::processResponse($rawResponse, $contentType);
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
//     protected function defaultReturnUrl()
//     {
//         $params = $_GET;
//         unset($params['code']);
//         unset($params['state']);
//         $params[0] = Yii::$app->controller->getRoute();
//         return Yii::$app->getUrlManager()->createAbsoluteUrl($params);
//     }
//     /**
//      * @inheritdoc
//      */
//     protected function defaultName()
//     {
//         return 'qq';
//     }
//     /**
//      * @inheritdoc
//      */
//     protected function defaultTitle()
//     {
//         return 'QQ';
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
//  * QQ allows authentication via QQ OAuth.
//  *
//  * In order to use QQ OAuth you must register your application at <http://connect.qq.com/>.
//  *
//  * Example application configuration:
//  *
//  * ~~~
//  * 'components' => [
//  *     'authClientCollection' => [
//  *         'class' => 'yii\authclient\Collection',
//  *         'clients' => [
//  *             'qq' => [
//  *                 'class' => 'lonelythinker\yii2\authclient\Qq',
//  *                 'clientId' => 'qq_appid',
//  *                 'clientSecret' => 'qq_appkey',
//  *             ],
//  *         ],
//  *     ]
//  *     ...
//  * ]
//  * ~~~
//  *
//  * @see http://connect.qq.com/
//  * @see http://wiki.connect.qq.com/
//  *
//  * @author : lonelythinker
//  * @email : 710366112@qq.com
//  * @homepage : www.lonelythinker.cn
//  */
// class Qq extends OAuth2
// {
//     /**
//      * @inheritdoc
//      */
//     public $authUrl = 'https://graph.qq.com/oauth2.0/authorize';
//     /**
//      * @inheritdoc
//      */
//     public $tokenUrl = 'https://graph.qq.com/oauth2.0/token';
//     /**
//      * @inheritdoc
//      */
//     public $apiBaseUrl = 'https://graph.qq.com';
//     /**
//      * @inheritdoc
//      */
//     public function init()
//     {
//         parent::init();
//         if ($this->scope === null) {
//             $this->scope = implode(' ', [
//                 'get_user_info',
//             ]);
//         }
//     }
//     /**
//      * @inheritdoc
//      */
//     public function buildAuthUrl(array $params = [])
//     {
//         $authState = $this->generateAuthState();
//         $this->setState('authState', $authState);
//         $params['state'] = $authState;
//         return parent::buildAuthUrl($params);
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
//         return parent::fetchAccessToken($authCode, $params);
//     }
//     /**
//      * @inheritdoc
//      */
//     protected function defaultNormalizeUserAttributeMap()
//     {
//         return [
//             'username' => 'nickname',
//         ];
//     }
//     /**
//      * @inheritdoc
//      */
//     protected function initUserAttributes()
//     {
//         $user = $this->api('oauth2.0/me', 'GET');
//         if ( isset($user['error']) ) {
//             throw new HttpException(400, $user['error']. ':'. $user['error_description']);
//         }
//         $userAttributes = $this->api(
//             "user/get_user_info",
//             'GET',
//             [
//                 'oauth_consumer_key' => $user['client_id'],
//                 'openid' => $user['openid'],
//             ]
//         );
//         $userAttributes['id'] = $user['openid'];
//         return $userAttributes;
//     }
//     /**
//      * @inheritdoc
//      */
//     protected function processResponse($rawResponse, $contentType = self::CONTENT_TYPE_AUTO)
//     {
//         if ($contentType === self::CONTENT_TYPE_AUTO && strpos($rawResponse, "callback(") === 0) {
//             $count = 0;
//             $jsonData = preg_replace('/^callback\(\s*(\\{.*\\})\s*\);$/is', '\1', $rawResponse, 1, $count);
//             if ($count === 1) {
//                 $rawResponse = $jsonData;
//                 $contentType = self::CONTENT_TYPE_JSON;
//             }
//         }
//         return parent::processResponse($rawResponse, $contentType);
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
//     protected function defaultReturnUrl()
//     {
//         $params = $_GET;
//         unset($params['code']);
//         unset($params['state']);
//         $params[0] = Yii::$app->controller->getRoute();
//         return Yii::$app->getUrlManager()->createAbsoluteUrl($params);
//     }
//     /**
//      * @inheritdoc
//      */
//     protected function defaultName()
//     {
//         return 'qq';
//     }
//     /**
//      * @inheritdoc
//      */
//     protected function defaultTitle()
//     {
//         return 'QQ';
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
// class Qq extends OAuth2
// {
//     public $authUrl = 'https://graph.qq.com/oauth2.0/authorize';
    
//     public $tokenUrl = 'https://graph.qq.com/oauth2.0/token';
    
//     public $apiBaseUrl = 'https://graph.qq.com';
    
//     /**
//      * @inheritdoc
//      */
//     public function init()
//     {
//         parent::init();
//         if ($this->scope === null) {
//             $this->scope = 'get_user_info';
//         }
//     }
    
// //    public function fetchAccessToken($authCode, array $params = [])
// //    {
// //        $defaultParams = [
// //            'appid' => $this->clientId,
// //            'secret' => $this->clientSecret,
// //            'code' => $authCode,
// //            'grant_type' => 'authorization_code',
// //        ];
// //
// //        $request = $this->createRequest()
// //            ->setMethod('POST')
// //            ->setUrl($this->tokenUrl)
// //            ->setData(array_merge($defaultParams, $params));
// //
// //        $response = $this->sendRequest($request);
// //
// //        $token = $this->createToken(['params' => $response]);
// //        $this->setAccessToken($token);
// //
// //        return $token;
// //    }
    
//     public function applyAccessTokenToRequest($request, $accessToken)
//     {
//         $data = $request->getData();
//         $data['access_token'] = $accessToken->getToken();
//         $data['openid'] = $accessToken->getParam('openid');
//         $request->setData($data);
//     }
    
//     protected function initUserAttributes()
//     {
//         return $this->api('oauth2.0/me', 'GET');
//     }
    
//     public function getUserInfo()
//     {
//         return $this->api("user/get_user_info", 'GET', [
//             'oauth_consumer_key' => $this->clientId,
//         ]);
//     }
    
//     /**
//      * @inheritdoc
//      */
//     protected function defaultName()
//     {
//         return 'qq';
//     }
//     /**
//      * @inheritdoc
//      */
//     protected function defaultTitle()
//     {
//         return 'QQ';
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
// use yii\httpclient\Client;
// use yii\web\HttpException;
// use yii\httpclient\Response;
// use yii\authclient\OAuth2;
// use yii\authclient\InvalidResponseException;
// /**
//  * Class Qq
//  * @package xutl\authclient
//  */
// class Qq extends OAuth2
// {
//     /**
//      * @inheritdoc
//      */
//     public $authUrl = 'https://graph.qq.com/oauth2.0/authorize';
//     /**
//      * @inheritdoc
//      */
//     public $tokenUrl = 'https://graph.qq.com/oauth2.0/token';
//     /**
//      * @inheritdoc
//      */
//     public $apiBaseUrl = 'https://graph.qq.com';
//     /**
//      * @inheritdoc
//      */
//     public function init()
//     {
//         parent::init();
//         if ($this->scope === null) {
//             $this->scope = implode(',', ['get_user_info']);
//         }
//     }
//     /**
//      * @inheritdoc
//      */
//     protected function defaultNormalizeUserAttributeMap()
//     {
//         return [
//             'username' => 'nickname',
//         ];
//     }
//     /**
//      * @inheritdoc
//      */
//     protected function initUserAttributes()
//     {
//         $user = $this->api('oauth2.0/me', 'GET');
//         if (isset($user['error'])) {
//             throw new HttpException(400, $user['error'] . ':' . $user['error_description']);
//         }
//         $userAttributes = $this->api(
//             "user/get_user_info",
//             'GET',
//             [
//                 'oauth_consumer_key' => $user['client_id'],
//                 'openid' => $user['openid'],
//             ]
//         );
//         $userAttributes['id'] = $user['openid'];
//         return $userAttributes;
//     }
//     /**
//      * Sends the given HTTP request, returning response data.
//      * @param \yii\httpclient\Request $request HTTP request to be sent.
//      * @return array response data.
//      * @throws InvalidResponseException on invalid remote response.
//      * @since 2.1
//      */
//     protected function sendRequest($request)
//     {
//         $response = $request->send();
//         if (!$response->getIsOk()) {
//             throw new InvalidResponseException($response, 'Request failed with code: ' . $response->getStatusCode() . ', message: ' . $response->getContent());
//         }
//         $this->processResult($response);
//         return $response->getData();
//     }
//     /**
//      * 处理响应
//      * @param Response $response
//      * @since 2.1
//      */
//     protected function processResult(Response $response)
//     {
//         $content = $response->getContent();
//         if (strpos($content, "callback(") === 0) {
//             $count = 0;
//             $jsonData = preg_replace('/^callback\(\s*(\\{.*\\})\s*\);$/is', '\1', $content, 1, $count);
//             if ($count === 1) {
//                 $response->setContent($jsonData);
//             }
//         }
//     }
//     /**
//      * @inheritdoc
//      */
//     protected function defaultName()
//     {
//         return 'qq';
//     }
//     /**
//      * @inheritdoc
//      */
//     protected function defaultTitle()
//     {
//         return 'QQ';
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