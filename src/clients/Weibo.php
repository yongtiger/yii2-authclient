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
Array
(
    [id] => 5551861170
    [idstr] => 5551861170
    [class] => 1
    [screen_name] => 老虎杨光
    [name] => 老虎杨光
    [province] => 11
    [city] => 2
    [location] => 北京 西城区
    [description] => 
    [url] => 
    [profile_image_url] => http://tva3.sinaimg.cn/default/images/default_avatar_male_50.gif
    [cover_image_phone] => http://ww1.sinaimg.cn/crop.0.0.640.640.640/549d0121tw1egm1kjly3jj20hs0hsq4f.jpg
    [profile_url] => u/5551861170
    [domain] => 
    [weihao] => 
    [gender] => m
    [followers_count] => 2
    [friends_count] => 33
    [pagefriends_count] => 1
    [statuses_count] => 9
    [favourites_count] => 0
    [created_at] => Thu Mar 12 20:57:07 +0800 2015
    [following] => 
    [allow_all_act_msg] => 
    [geo_enabled] => 1
    [verified] => 
    [verified_type] => -1
    [remark] => 
    [insecurity] => Array
        (
            [sexual_content] => 
        )

    [status] => Array
        (
            [created_at] => Tue Apr 19 01:46:20 +0800 2016
            [id] => 3.9657904218841E+15
            [mid] => 3965790421884069
            [idstr] => 3965790421884069
            [text] => 每位公益车APP立足于公益平台，以全免费的服务，为广大热心的网民，提供互助拼车、空闲车位、共享充电桩等公益互助服务，最大限度利用公共资源、节能环保、减少社会压力与矛盾。；体验地址：http://t.cn/RqKzNDE
            [textLength] => 196
            [source_allowclick] => 0
            [source_type] => 1
            [source] => 每位公益车
            [favorited] => 
            [truncated] => 
            [in_reply_to_status_id] => 
            [in_reply_to_user_id] => 
            [in_reply_to_screen_name] => 
            [pic_urls] => Array
                (
                )

            [geo] => 
            [reposts_count] => 0
            [comments_count] => 0
            [attitudes_count] => 0
            [isLongText] => 
            [mlevel] => 0
            [visible] => Array
                (
                    [type] => 0
                    [list_id] => 0
                )

            [biz_feature] => 0
            [hasActionTypeCard] => 0
            [darwin_tags] => Array
                (
                )

            [hot_weibo_tags] => Array
                (
                )

            [text_tag_tips] => Array
                (
                )

            [userType] => 0
            [positive_recom_flag] => 0
            [gif_ids] => 
            [is_show_bulletin] => 0
        )

    [ptype] => 0
    [allow_all_comment] => 1
    [avatar_large] => http://tva3.sinaimg.cn/default/images/default_avatar_male_180.gif
    [avatar_hd] => http://tva3.sinaimg.cn/default/images/default_avatar_male_180.gif
    [verified_reason] => 
    [verified_trade] => 
    [verified_reason_url] => 
    [verified_source] => 
    [verified_source_url] => 
    [follow_me] => 
    [online_status] => 0
    [bi_followers_count] => 0
    [lang] => zh-cn
    [star] => 0
    [mbtype] => 0
    [mbrank] => 0
    [block_word] => 0
    [block_app] => 0
    [credit_score] => 80
    [user_ability] => 0
    [urank] => 4
)
 * @see https://developer.weibo.com/
 */
class Weibo extends OAuth2 implements IAuth
{
    use ClientTrait;

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