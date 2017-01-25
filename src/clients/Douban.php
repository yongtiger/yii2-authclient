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
 * Douban OAuth2
 *
 * In order to use Douban OAuth2 you must register your application at <https://www.douban.com/>.
 *
 * Note: No test!!!
 *
 * Example application configuration:
 *
 * ```php
 * 'components' => [
 *     'authClientCollection' => [
 *         'class' => 'yii\authclient\Collection',
 *         'clients' => [
 *             'douban' => [
 *                 'class' => 'yii\authclient\clients\Douban',
 *                 'clientId' => 'douban_client_id',
 *                 'clientSecret' => 'douban_client_secret',
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
 * https://www.douban.com/service/auth2/auth?client_id=0b5405e19c58e4cc21fc11a4d50aae64&redirect_uri=https://www.example.com/back&response_type=code&scope=shuo_basic_r,shuo_basic_w,douban_basic_common
 * ```
 *
 * AccessToken Request:
 *
 * ```
 * https://www.douban.com/service/auth2/token
 * ```
 *
 * AccessToken Response:
 *
 * ```
 * {"access_token":"a14afef0f66fcffce3e0fcd2e34f6ff4","expires_in":3920,"refresh_token":"5d633d136b6d56a41829b73a424803ec","douban_user_id":"1221"}
 * ```
 *
 * Request of `initUserAttributes()`:
 *
 * ```
 * https://api.douban.com/v2/user/~me
 * ```
 *
 * ```
 * curl "https://api.douban.com/v2/user/~me" -H "Authorization: Bearer a14afef0f66fcffce3e0fcd2e34f6ff4"
 * ```
 *
 * Response of `initUserAttributes()`:
 *
 * ```
 * {
 *    "id": "1000001",
 *    "uid": "ahbei",
 *    "name": "阿北",
 *    "avatar": "https://img1.doubanio.com/icon/u1000001-28.jpg", //头像小图
 *    "alt": "https://www.douban.com/people/ahbei/",
 *    "relation": "contact", //和当前登录用户的关系，friend或contact
 *    "created": "2006-01-09 21:12:47", //注册时间
 *    "loc_id": "108288", //城市id
 *    "loc_name": "北京", //所在地全称
 *    "desc": "     现在多数时间在忙忙碌碌地为豆瓣添砖加瓦。坐在马桶上看书，算是一天中最放松的时间。
 *    我不但喜欢读书、旅行和音乐电影，还曾经是一个乐此不疲的实践者，有一墙碟、两墙书、三大洲的车船票为记。
 *    (因为时间和数量的原因，豆邮和"@阿北"不能保证看到。有豆瓣的问题请email联系help@douban.com。)"
 * }
 * ```
 *
 * [REFERENCES]
 *
 * @see https://www.douban.com
 * @see http://developers.douban.com/wiki/?title=user_v2#User
 */
class Douban extends OAuth2 implements IAuth
{
    use ClientTrait;

    /**
     * @inheritdoc
     */
    public $authUrl = 'https://www.douban.com/service/auth2/auth';

    /**
     * @inheritdoc
     */
    public $tokenUrl = 'https://www.douban.com/service/auth2/token';

    /**
     * @inheritdoc
     */
    public $apiBaseUrl = 'https://api.douban.com';

    /**
     * @inheritdoc
     */
    public $scope = 'douban_basic_common';

    /**
     * @inheritdoc
     */
    protected function defaultName()
    {
        return 'douban';
    }

    /**
     * @inheritdoc
     */
    protected function defaultTitle()
    {
        return 'Douban';
    }

    /**
     * @inheritdoc
     */
    public $attributeNames = [
        'name',
        'avatar',
        'alt',
    ];

    /**
     * @inheritdoc
     */
    protected function defaultViewOptions()
    {
        return [
            'popupWidth'  => 800,
            'popupHeight' => 500,
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
            'openid' => 'id',
            'fullname' => 'name',
            'link' => 'alt',
        ];
    }

    /**
     * @inheritdoc
     */
    protected function initUserAttributes()
    {
        return $this->api('v2/user/~me', 'GET', [], ['Authorization' => 'Bearer ' . $this->getAccessToken()->getToken()]);   ///add Bearer token for api requests. @see https://developers.douban.com/wiki/?title=connect
    }
}
