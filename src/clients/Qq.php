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
use yii\base\Event;
use yii\httpclient\Request;

/**
 * QQ OAuth2
 *
 * In order to use QQ OAuth2 you must register your application at <https://connect.qq.com/>.
 *
 * Note: Authorization `Callback URL` can contain `localhost` or `127.0.0.1` for testing, but CANNOT contain `?` or `&`.
 *
 * Sample `Callback URL`:
 *
 * `http://localhost/1_oauth/frontend/web/index.php/site/auth` (OK)
 * `http://localhost/1_oauth/frontend/web/index.php/site/auth?authclient=qq` (WRONG!)
 * `http://localhost/1_oauth/frontend/web/index.php?r=site/auth` (WRONG!)
 * `http://localhost/1_oauth/frontend/web/index.php` (WRONG!)
 * `http://localhost/1_oauth/frontend/web` (WRONG!)
 * `http://localhost` (WRONG!)
 * `http://127.0.0.1/1_oauth/frontend/web/index.php/site/auth` (OK)
 * `http://127.0.0.1/1_oauth/frontend/web/index.php/site/auth?authclient=qq` (WRONG!)
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
 * [EXAMPLE RESPONSE]
 *
 * Authorization URL:
 *
 * ```
 * https://graph.qq.com/oauth2.0/authorize? client_id=101367642&response_type=code&redirect_uri=http%3A%2F%2Flocalhost%2F1_oauth %2Ffrontend%2Fweb%2Findex.php%2Fsite%2Fauth%3Fauthclient%3Dqq&xoauth_displayname=My%20Application&state=9362e07462ea0df3bb69ddc0064f89f2e59c2fde963c5063b5cd3945bd641ed7
 * ```
 *
 * AccessToken Request:
 *
 * ```
 * https://graph.qq.com/oauth2.0/token
 * ```
 *
 * AccessToken Response:
 *
 * ```
 * access_token=44FB4E6840CC185DA4508176856A7B42&expires_in=7776000&refresh_token=49367EAC 553A22A8995666892D805F7C
 * ```
 *
 * Request of `initUserAttributes()`:
 *
 * ```
 * https://graph.qq.com/oauth2.0/me?access_token=44FB4E6840CC185DA4508176856A7B42
 * ```
 *
 * Response of `initUserAttributes()`:
 *
 * ```
 * callback( {"client_id":"101367642","openid":"9B18299BC4CBFDAFCA09DBE95A6A0F23"} );
 * ```
 *
 * ```php
 * Array
 * (
 *     [client_id] => 101367642
 *     [provider] => qq
 *     [openid] => 9B18299BC4CBFDAFCA09DBE95A6A0F23
 * )
 * ```
 *
 * Request of `initUserInfoAttributes()`:
 *
 * ```
 * https://graph.qq.com/user/get_user_info?oauth_consumer_key=101367642&openid=42E5367A39543562249CA32DA627D8A2&access_token=8E2400CC08A22A5F438CC3B5E1345166
 * ```
 *
 * Response of `initUserInfoAttributes()`:
 *
 * ```
 * { "ret": 0, "msg": "", "is_lost":0, "nickname": "tiger_yang", "gender": "男", "province": "北京", "city": "东城", "year": "1971", "figureurl": "http:\/\/qzapp.qlogo.cn\/qzapp\/101367642\/42E5367A39543562249CA32DA627D8A2\/30", "figureurl_1": "http:\/\/qzapp.qlogo.cn\/qzapp\/101367642\/42E5367A39543562249CA32DA627D8A2\/50", "figureurl_2": "http:\/\/qzapp.qlogo.cn\/qzapp\/101367642\/42E5367A39543562249CA32DA627D8A2\/100", "figureurl_qq_1": "http:\/\/q.qlogo.cn\/app\/101367642\/42E5367A39543562249CA32DA627D8A2\/40", "figureurl_qq_2": "http:\/\/q.qlogo.cn\/qqapp\/101367642\/42E5367A39543562249CA32DA627D8A2\/100", "is_yellow_vip": "0", "vip": "0", "yellow_vip_level": "0", "level": "0", "is_yellow_year_vip": "0" }
 * ```
 *
 * ```php
 * Array
 * (
 *     [client_id] => 101367642
 *     [openid] => 42E5367A39543562249CA32DA627D8A2
 *     [ret] => 0
 *     [msg] => 
 *     [is_lost] => 0
 *     [nickname] => tiger_yang
 *     [gender] => 1
 *     [province] => 北京
 *     [city] => 东城
 *     [year] => 1971
 *     [figureurl] => http://qzapp.qlogo.cn/qzapp/101367642/42E5367A39543562249CA32DA627D8A2/30
 *     [figureurl_1] => http://qzapp.qlogo.cn/qzapp/101367642/42E5367A39543562249CA32DA627D8A2/50
 *     [figureurl_2] => http://qzapp.qlogo.cn/qzapp/101367642/42E5367A39543562249CA32DA627D8A2/100
 *     [figureurl_qq_1] => http://q.qlogo.cn/qqapp/101367642/42E5367A39543562249CA32DA627D8A2/40
 *     [figureurl_qq_2] => http://q.qlogo.cn/qqapp/101367642/42E5367A39543562249CA32DA627D8A2/100
 *     [is_yellow_vip] => 0
 *     [vip] => 0
 *     [yellow_vip_level] => 0
 *     [level] => 0
 *     [is_yellow_year_vip] => 0
 *     [provider] => qq
 *     [fullname] => tiger_yang
 *     [email] => tiger_yang@qq.com
 *     [avatar] => http://qzapp.qlogo.cn/qzapp/101367642/42E5367A39543562249CA32DA627D8A2/100
 * )
 * ```
 *
 * [REFERENCES]
 *
 * @see https://connect.qq.com/
 * @see http://wiki.connect.qq.com/
 * @see http://wiki.connect.qq.com/openapi%e8%b0%83%e7%94%a8%e8%af%b4%e6%98%8e_oauth2-0
 * @see http://wiki.connect.qq.com/oauth2-0%e5%bc%80%e5%8f%91%e6%96%87%e6%a1%a3
 * @see http://wiki.connect.qq.com/%e8%8e%b7%e5%8f%96%e7%94%a8%e6%88%b7openid_oauth2-0
 */
class Qq extends OAuth2 implements IAuth
{
    use ClientTrait;

    /**
     * @inheritdoc
     */
    public $authUrl = 'https://graph.qq.com/oauth2.0/authorize';

    /**
     * @inheritdoc
     */
    public $tokenUrl = 'https://graph.qq.com/oauth2.0/token';

    /**
     * @inheritdoc
     */
    public $apiBaseUrl = 'https://graph.qq.com';

    /**
     * @inheritdoc
     */
    protected function defaultName()
    {
        return 'qq';
    }

    /**
     * @inheritdoc
     */
    protected function defaultTitle()
    {
        return 'Qq';
    }

    /**
     * @inheritdoc
     */
    protected function defaultViewOptions()
    {
        return [
            'popupWidth' => 480,
            'popupHeight' => 600,
        ];
    }

    /**
     * Normalize user info attribute map.
     *
     * @return array
     */
    protected function userInfoNormalizeUserAttributeMap() {
        return [
            'provider' => function ($attributes) {
                return $this->defaultName();
            },
            'fullname' => 'nickname',
            'email' => function ($attributes) {
                return isset($attributes['nickname']) ? $attributes['nickname'] . '@qq.com' : null;
            },
            'gender' => function ($attributes) {
                if (!isset($attributes['gender'])) return null;
                return ($attributes['gender'] == '男' ? static::GENDER_MALE : ($attributes['gender'] == '女' ? static::GENDER_FEMALE : null));
            },
            'avatar' => 'figureurl_2',   ///you can choose others
        ];
    }

    /**
     * Get user openid and other basic information.
     *
     * @return array
     */
    protected function initUserAttributes()
    {
        Event::on(Request::className(), Request::EVENT_AFTER_SEND, function ($event) {
            ///Replace `callback( {"client_id":"101367642","openid":"9B18299BC4CBFDAFCA09DBE95A6A0F23"} );` with ` {"client_id":"101367642","openid":"9B18299BC4CBFDAFCA09DBE95A6A0F23"}`
            $event->response->content = preg_replace('/^callback\(\s*(\{.*\})\s*\);$/is', '\1', $event->response->content);
        });
        return $this->api('oauth2.0/me', 'GET');
    }

    /**
     * Get extra user info.
     *
     * @return array
     */
    protected function initUserInfoAttributes()
    {
        return $this->api("user/get_user_info", 'GET', ['oauth_consumer_key'=>$this->getUserAttributes()['client_id'], 'openid'=>$this->getUserAttributes()['openid']]);
    }
}
