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
 * Weibo OAuth2
 *
 * In order to use Weibo OAuth2 you must register your application at <https://developer.weibo.com>.
 *
 * Example application configuration:
 *
 * ```php
 * 'components' => [
 *     'authClientCollection' => [
 *         'class' => 'yii\authclient\Collection',
 *         'clients' => [
 *             'weibo' => [
 *                 'class' => 'yii\authclient\clients\Weibo',
 *                 'clientId' => 'weibo_client_id',
 *                 'clientSecret' => 'weibo_client_secret',
 *             ],
 *         ],
 *     ]
 *     // ...
 * ]
 * ```
 *
 * @see https://developer.weibo.com/
 */
class Weibo extends OAuth2 implements IAuth
{
    public $authUrl = 'https://api.weibo.com/oauth2/authorize';
    public $tokenUrl = 'https://api.weibo.com/oauth2/access_token';
    public $apiBaseUrl = 'https://api.weibo.com';
    /**
     *
     * @return []
     * @see http://open.weibo.com/wiki/Oauth2/get_token_info
     * @see http://open.weibo.com/wiki/2/users/show
     */
    protected function initUserAttributes()
    {
        $openid = $this->api('oauth2/get_token_info', 'POST');
        return $this->api("2/users/show.json", 'GET', ['uid' => $openid['uid']]);
    }
    protected function defaultName()
    {
        return 'weibo';
    }
    protected function defaultTitle()
    {
        return 'Weibo';
    }
}

// <?php

// namespace frontend\widgets;

// use yii\authclient\OAuth2;
// use yii\web\HttpException;
// use Yii;

// class WeiboClient extends OAuth2
// {
//     public $authUrl = 'https://api.weibo.com/oauth2/authorize';

//     public $tokenUrl = 'https://api.weibo.com/oauth2/access_token';

//     public $apiBaseUrl = 'https://api.weibo.com/2';

//     protected function initUserAttributes()
//     {
//         $user = $this->api('users/show.json', 'GET', ['uid' => $this->user->uid]);

//         return [
//             'client' => 'weibo',
//             'openid' => $user['id'],
//             'nickname' => $user['name'],
//             'gender' => $user['gender'],
//             'location' => $user['location'],
//         ];
//     }


//     /**
//      * @inheritdoc
//      */
//     protected function getUser()
//     {
//         $str = file_get_contents('https://api.weibo.com/2/account/get_uid.json?access_token=' . $this->accessToken->token);
//         return json_decode($str);
//     }

//     /**
//      * @inheritdoc
//      */
//     protected function defaultName()
//     {
//         return 'Weibo';
//     }

//     /**
//      * @inheritdoc
//      */
//     protected function defaultTitle()
//     {
//         return '微博登录';
//     }
// }

// <?php
// namespace yujiandong\authclient;
// use yii\authclient\OAuth2;
// /**
//  * Weibo allows authentication via Weibo OAuth.
//  *
//  * In order to use Weibo OAuth you must register your application at <http://open.weibo.com/>.
//  *
//  * Example application configuration:
//  *
//  * ~~~
//  * 'components' => [
//  *     'authClientCollection' => [
//  *         'class' => 'yii\authclient\Collection',
//  *         'clients' => [
//  *             'weibo' => [
//  *                 'class' => 'yujiandong\authclient\Weibo',
//  *                 'clientId' => 'wb_key',
//  *                 'clientSecret' => 'wb_secret',
//  *             ],
//  *         ],
//  *     ]
//  *     ...
//  * ]
//  * ~~~
//  *
//  * @see http://open.weibo.com/
//  * @see http://open.weibo.com/wiki/
//  *
//  * @author Jiandong Yu <flyyjd@gmail.com>
//  */
// class Weibo extends OAuth2
// {
//     /**
//      * @inheritdoc
//      */
//     public $authUrl = 'https://api.weibo.com/oauth2/authorize';
//     /**
//      * @inheritdoc
//      */
//     public $tokenUrl = 'https://api.weibo.com/oauth2/access_token';
//     /**
//      * @inheritdoc
//      */
//     public $apiBaseUrl = 'https://api.weibo.com';
//     /**
//      * @inheritdoc
//      */
//     protected function defaultNormalizeUserAttributeMap()
//     {
//         return [
//             'username' => 'name',
//         ];
//     }
//     /**
//      * @inheritdoc
//      */
//     protected function initUserAttributes()
//     {
//         $openid = $this->api('oauth2/get_token_info', 'POST');
//         return $this->api("2/users/show.json", 'GET', ['uid' => $openid['uid']]);
//     }
//     /**
//      * @inheritdoc
//      */
//     protected function defaultName()
//     {
//         return 'weibo';
//     }
//     /**
//      * @inheritdoc
//      */
//     protected function defaultTitle()
//     {
//         return 'Weibo';
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
// /**
//  * Weibo allows authentication via Weibo OAuth.
//  *
//  * In order to use Weibo OAuth you must register your application at <http://open.weibo.com/>.
//  *
//  * Example application configuration:
//  *
//  * ~~~
//  * 'components' => [
//  *     'authClientCollection' => [
//  *         'class' => 'yii\authclient\Collection',
//  *         'clients' => [
//  *             'weibo' => [
//  *                 'class' => 'yujiandong\authclient\Weibo',
//  *                 'clientId' => 'wb_key',
//  *                 'clientSecret' => 'wb_secret',
//  *             ],
//  *         ],
//  *     ]
//  *     ...
//  * ]
//  * ~~~
//  *
//  * @see http://open.weibo.com/
//  * @see http://open.weibo.com/wiki/
//  *
//  * @author : lonelythinker
//  * @email : 710366112@qq.com
//  * @homepage : www.lonelythinker.cn
//  */
// class Weibo extends OAuth2
// {
//     /**
//      * @inheritdoc
//      */
//     public $authUrl = 'https://api.weibo.com/oauth2/authorize';
//     /**
//      * @inheritdoc
//      */
//     public $tokenUrl = 'https://api.weibo.com/oauth2/access_token';
//     /**
//      * @inheritdoc
//      */
//     public $apiBaseUrl = 'https://api.weibo.com';
//     /**
//      * @inheritdoc
//      */
//     protected function defaultNormalizeUserAttributeMap()
//     {
//         return [
//                 'username' => 'name',
//         ];
//     }
//     /**
//      * @inheritdoc
//      */
//     protected function initUserAttributes()
//     {
//         $openid = $this->api('oauth2/get_token_info', 'POST');
//         return $this->api("2/users/show.json", 'GET', ['uid' => $openid['uid']]);
//     }
//     /**
//      * @inheritdoc
//      */
//     protected function defaultName()
//     {
//         return 'weibo';
//     }
//     /**
//      * @inheritdoc
//      */
//     protected function defaultTitle()
//     {
//         return 'Weibo';
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
// class Weibo extends OAuth2
// {
//     public $authUrl = 'https://api.weibo.com/oauth2/authorize';
    
//     public $tokenUrl = 'https://api.weibo.com/oauth2/access_token';
    
//     public $apiBaseUrl = 'https://api.weibo.com';
    
//     /**
//      *
//      * @return []
//      * @see http://open.weibo.com/wiki/Oauth2/get_token_info
//      * @see http://open.weibo.com/wiki/2/users/show
//      */
//     protected function initUserAttributes()
//     {
//         return $this->api('oauth2/get_token_info', 'POST');
//     }
    
//     /**
//      * get UserInfo
//      * @return []
//      * @see http://open.weibo.com/wiki/2/users/show
//      */
//     public function getUserInfo()
//     {
//         $userAttributes = $this->getUserAttributes();
//         return $this->api("2/users/show.json", 'GET', ['uid' => $userAttributes['uid']]);
//     }
    
//     public function applyAccessTokenToRequest($request, $accessToken)
//     {
//         $data = $request->getData();
//         $data['access_token'] = $accessToken->getToken();
//         $request->setData($data);
//     }
    
//     /**
//      * @inheritdoc
//      */
//     protected function defaultName()
//     {
//         return 'weibo';
//     }
//     /**
//      * @inheritdoc
//      */
//     protected function defaultTitle()
//     {
//         return 'Weibo';
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
// use yii\authclient\OAuth2;
// class Weibo extends OAuth2
// {
//     /**
//      * @inheritdoc
//      */
//     public $authUrl = 'https://api.weibo.com/oauth2/authorize';
//     /**
//      * @inheritdoc
//      */
//     public $tokenUrl = 'https://api.weibo.com/oauth2/access_token';
//     /**
//      * @inheritdoc
//      */
//     public $apiBaseUrl = 'https://api.weibo.com';
//     /**
//      * @inheritdoc
//      */
//     public function init()
//     {
//         parent::init();
//         if ($this->scope === null) {
//             $this->scope = implode(',', [
//                 'follow_app_official_microblog',
//             ]);
//         }
//     }
//     /**
//      * @inheritdoc
//      */
//     protected function initUserAttributes()
//     {
//         return $this->api("2/users/show.json", 'GET', ['uid' => $this->getOpenId()]);
//     }
//     /**
//      * 返回OpenId
//      * @return mixed
//      */
//     public function getOpenId()
//     {
//         $tokenInfo = $this->api('oauth2/get_token_info', 'POST');
//         return $tokenInfo['uid'];
//     }
//     /**
//      * @inheritdoc
//      */
//     protected function defaultName()
//     {
//         return 'weibo';
//     }
//     /**
//      * @inheritdoc
//      */
//     protected function defaultTitle()
//     {
//         return Yii::t('app', 'Weibo');
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