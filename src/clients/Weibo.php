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
 * In order to use Weibo OAuth2 you must register your application at <http://open.weibo.com/>.
 *
 * Note: Authorization `Callback URL` can contain `localhost` or `127.0.0.1` for testing.
 *
 * Sample `Callback URL`:
 *
 * `http://localhost/1_oauth/frontend/web/index.php/site/auth` (WRONG!)
 * `http://localhost/1_oauth/frontend/web/index.php/site/auth?authclient=weibo` (OK)
 * `http://localhost/1_oauth/frontend/web/index.php?r=site/auth&authclient=weibo` (WRONG!)
 * `http://localhost/1_oauth/frontend/web/index.php?r=site/auth` (WRONG!)
 * `http://localhost/1_oauth/frontend/web/index.php` (WRONG!)
 * `http://localhost/1_oauth/frontend/web` (WRONG!)
 * `http://localhost` (WRONG!)
 * `http://127.0.0.1/1_oauth/frontend/web/index.php/site/auth` (WRONG!)
 * `http://127.0.0.1/1_oauth/frontend/web/index.php/site/auth?authclient=weibo` (OK)
 * `http://127.0.0.1/1_oauth/frontend/web/index.php?r=site/auth&authclient=weibo` (WRONG!)
 * `http://127.0.0.1/1_oauth/frontend/web/index.php?r=site/auth` (WRONG!)
 * `http://127.0.0.1/1_oauth/frontend/web/index.php` (WRONG!)
 * `http://127.0.0.1/1_oauth/frontend/web` (WRONG!)
 * `http://127.0.0.1` (WRONG!)
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
 * [EXAMPLE RESPONSE]
 *
 * Authorization URL:
 *
 * ```
 * https://api.weibo.com/oauth2/authorize?
 client_id=812398870&response_type=code&redirect_uri=http%3A%2F%2Flocalhost%2F1_oauth%2Ffrontend%2Fweb%2Findex.php%2Fsite%2Fauth%3Fauthclient%3Dweibo&xoauth_displayname=My%20Application&state=c34d4c461a444a4b525cdb3eb6f1946693ab00975a06fac8f47ef244a3b3ae9e
 * ```
 *
 * AccessToken Request:
 *
 * ```
 * https://api.weibo.com/oauth2/access_token
 * ```
 *
 * AccessToken Response:
 *
 * ```
 * {"access_token":"2.00uhCjDG0aDkysa7910291bb0h7lbz","remind_in":"157679999","expires_in":157679999,"uid":"5551861170"}
 * ```
 *
 * Request of `initUserAttributes()`:
 *
 * ```
 * https://api.weibo.com/2/oauth2/get_token_info
 * ```
 *
 * Response of `initUserAttributes()`:
 *
 * ```
 * {"uid":5551861170,"appkey":"812398870","scope":null,"create_at":1484864682,"expire_in":157679944}
 * ```
 *
 * ```php
 * Array
 * (
 *     [uid] => 5551861170
 *     [appkey] => 812398870
 *     [scope] => 
 *     [create_at] => 1484865109
 *     [expire_in] => 157679998
 *     [openid] => 5551861170
 * )
 * ```
 *
 * Request of `initUserInfoAttributes()`:
 *
 * ```
 * https://api.weibo.com/2/users/show.json?uid=5551861170&access_token=2.00uhCjDG0aDkysa7910291bb0h7lbz
 * ```
 *
 * Response of `initUserInfoAttributes()`:
 *
 * ```
 * {"id":5551861170,"idstr":"5551861170","class":1,"screen_name":"老虎杨光","name":"老虎杨光","province":"11","city":"2","location":"北京 西城区","description":"","url":"","profile_image_url":"http://tva3.sinaimg.cn/default/images/default_avatar_male_50.gif","cover_image_phone":"http://ww1.sinaimg.cn/crop.0.0.640.640.640/549d0121tw1egm1kjly3jj20hs0hsq4f.jpg","profile_url":"u/5551861170","domain":"","weihao":"","gender":"m","followers_count":2,"friends_count":33,"pagefriends_count":1,"statuses_count":9,"favourites_count":0,"created_at":"Thu Mar 12 20:57:07 +0800 2015","following":false,"allow_all_act_msg":false,"geo_enabled":true,"verified":false,"verified_type":-1,"remark":"","ptype":0,"allow_all_comment":true,"avatar_large":"http://tva3.sinaimg.cn/default/images/default_avatar_male_180.gif","avatar_hd":"http://tva3.sinaimg.cn/default/images/default_avatar_male_180.gif","verified_reason":"","verified_trade":"","verified_reason_url":"","verified_source":"","verified_source_url":"","follow_me":false,"online_status":0,"bi_followers_count":0,"lang":"zh-cn","star":0,"mbtype":0,"mbrank":0,"block_wo":0,"block_app":0,"credit_score":80,"user_ability":0,"urank":4}
 * ```
 *
 * ```php
 * Array
 * (
 *     [uid] => 5551861170
 *     [appkey] => 812398870
 *     [scope] => 
 *     [create_at] => 1484865109
 *     [expire_in] => 157679998
 *     [openid] => 5551861170
 *     [id] => 5551861170
 *     [idstr] => 5551861170
 *     [class] => 1
 *     [screen_name] => 老虎杨光
 *     [name] => 老虎杨光
 *     [province] => 11
 *     [city] => 2
 *     [location] => 北京 西城区
 *     [description] => 
 *     [url] => 
 *     [profile_image_url] => http://tva3.sinaimg.cn/default/images/default_avatar_male_50.gif
 *     [cover_image_phone] => http://ww1.sinaimg.cn/crop.0.0.640.640.640/549d0121tw1egm1kjly3jj20hs0hsq4f.jpg
 *     [profile_url] => u/5551861170
 *     [domain] => 
 *     [weihao] => 
 *     [gender] => 1
 *     [followers_count] => 2
 *     [friends_count] => 33
 *     [pagefriends_count] => 1
 *     [statuses_count] => 9
 *     [favourites_count] => 0
 *     [created_at] => Thu Mar 12 20:57:07 +0800 2015
 *     [following] => 
 *     [allow_all_act_msg] => 
 *     [geo_enabled] => 1
 *     [verified] => 
 *     [verified_type] => -1
 *     [remark] => 
 *     [ptype] => 0
 *     [allow_all_comment] => 1
 *     [avatar_large] => http://tva3.sinaimg.cn/default/images/default_avatar_male_180.gif
 *     [avatar_hd] => http://tva3.sinaimg.cn/default/images/default_avatar_male_180.gif
 *     [verified_reason] => 
 *     [verified_trade] => 
 *     [verified_reason_url] => 
 *     [verified_source] => 
 *     [verified_source_url] => 
 *     [follow_me] => 
 *     [online_status] => 0
 *     [bi_followers_count] => 0
 *     [lang] => zh-cn
 *     [star] => 0
 *     [mbtype] => 0
 *     [mbrank] => 0
 *     [block_word] => 0
 *     [block_app] => 0
 *     [credit_score] => 80
 *     [user_ability] => 0
 *     [urank] => 4
 *     [provider] => weibo
 *     [fullname] => 老虎杨光
 *     [language] => zh-cn
 *     [avatar] => http://tva3.sinaimg.cn/default/images/default_avatar_male_50.gif
 *     [link] => 
 * )
 * ```
 *
 * [REFERENCES]
 *
 * @see http://open.weibo.com/
 * @see http://open.weibo.com/wiki/
 * @see http://open.weibo.com/wiki/Oauth2/get_token_info
 * @see http://open.weibo.com/wiki/2/users/show
 */
class Weibo extends OAuth2 implements IAuth
{
    use ClientTrait;

    /**
     * @inheritdoc
     */
    public $authUrl = 'https://api.weibo.com/oauth2/authorize';

    /**
     * @inheritdoc
     */
    public $tokenUrl = 'https://api.weibo.com/oauth2/access_token';

    /**
     * @inheritdoc
     */
    public $apiBaseUrl = 'https://api.weibo.com';

    /**
     * @inheritdoc
     */
    protected function defaultName()
    {
        return 'weibo';
    }

    /**
     * @inheritdoc
     */
    protected function defaultTitle()
    {
        return 'Weibo';
    }

    /**
     * @inheritdoc
     */
    protected function defaultViewOptions()
    {
        return [
            'popupWidth' => 720,
            'popupHeight' => 480,
        ];
    }

    /**
     * @inheritdoc
     */
    protected function defaultNormalizeUserAttributeMap() {
        return [
            'provider' => function ($attributes) {
                return $this->defaultName();
            },
            'openid' => 'uid',
        ];
    }

    /**
     * Normalize user info attribute map.
     *
     * @return array
     */
    protected function userInfoNormalizeUserAttributeMap() {
        return [
            'fullname' => 'name',
            'gender' => function ($attributes) {
                if (!isset($attributes['gender'])) return null;
                return ($attributes['gender'] == 'm' ? static::GENDER_MALE : ($attributes['gender'] == 'f' ? static::GENDER_FEMALE : null));
            },
            'language' => 'lang',
            'avatar' => 'profile_image_url',
            'link' => 'url',
        ];
    }

    /**
     * Get user openid and other basic information.
     *
     * @return array
     */
    protected function initUserAttributes()
    {
        return $this->api('/2/oauth2/get_token_info', 'POST');
    }

    /**
     * Get extra user info.
     *
     * @return array
     */
    protected function initUserInfoAttributes()
    {
        return $this->api("users/show.json", 'GET', ['uid' => $this->getUserAttributes()['uid']]);
    }
}
