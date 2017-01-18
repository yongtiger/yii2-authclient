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

/**
 * Yandex OAuth2
 *
 * In order to use Yandex OAuth2 you must register your application at <https://oauth.yandex.ru/client/new>.
 *
 * Note:  Authorization `Callback URL` can contain `localhost` or `127.0.0.1` for testing.
 *
 * Sample `Callback URL`: 
 * `http://localhost/1_oauth/frontend/web/index.php/site/auth?authclient=yandex` (OK)
 * `http://localhost/1_oauth/frontend/web/index.php?r=site/auth` (WRONG!)
 * `http://localhost/1_oauth/frontend/web/index.php?r=site/auth&authclient=yandex` (WRONG!)
 * 
 * Example application configuration:
 *
 * ```php
 * 'components' => [
 *     'authClientCollection' => [
 *         'class' => 'yii\authclient\Collection',
 *         'clients' => [
 *             'yandex' => [
 *                 'class' => 'yii\authclient\clients\Yandex',
 *                 'clientId' => 'yandex_client_id',
 *                 'clientSecret' => 'yandex_client_secret',
 *             ],
 *         ],
 *     ]
 *     // ...
 * ]
 * ```
 *
 * [EXAMPLE JSON RESPONSE BODY FOR GET]
 * 
 * `$responseContent` at `/vendor/yiisoft/yii2-httpclient/StreamTransport.php`:
 *
 * ```
    {
      "first_name": "\u0412\u0430\u0441\u044F",
      "last_name": "\u041F\u0443\u043F\u043A\u0438\u043D",
      "display_name": "Vasya",
      "emails": [
        "test@yandex.ru",
        "other-test@yandex.ru"
      ],
      "default_email": "test@yandex.ru",
      "real_name": "\u0412\u0430\u0441\u044F \u041F\u0443\u043F\u043A\u0438\u043D",
      "is_avatar_empty": false,
      "birthday": "1987-03-12",
      "default_avatar_id": "131652443",
      "openid_identities": [
        "http://openid.yandex.ru/vasya/",
        "http://vasya.ya.ru/"
      ],
      "login": "vasya",
      "old_social_login": "uid-mmzxrnry",
      "sex": "male",
      "id": "1000034426"
    }
 * ```
 *
Array
(
    [login] => yong.tiger
    [id] => 456664807
    [openid] => 456664807
    [gender] => 
    [avatarUrl] => 
)
 * [REFERENCES]
 *
 * @see https://oauth.yandex.ru/client/new
 * @see http://api.yandex.ru/login/doc/dg/reference/response.xml
 * @see https://tech.yandex.ru/passport/doc/dg/reference/response-docpage/
 * @see https://tech.yandex.com/oauth
 */
class Yandex extends \yii\authclient\clients\Yandex implements IAuth
{
    use ClientTrait;

    /**
     * @inheritdoc
     */
    protected function defaultViewOptions() {
        return [
            'popupWidth' => 600,
            'popupHeight' => 600,
        ];
    }

    /**
     * @inheritdoc
     */
    protected function defaultNormalizeUserAttributeMap() {
        return [
            'openid' => 'id',

            'email' => 'default_email',

            'fullname' => 'login',

            'firstname' => 'first_name',

            'lastname' => 'last_name',

            'gender' => function ($attributes) {
                if (!isset($attributes['sex'])) return null;
                return $attributes['sex'] == 'male' ? static::GENDER_MALE : ($attributes['sex'] == 'female' ? static::GENDER_FEMALE : null);
            },

            'language' => 'lang',

            'avatarUrl' => function ($attributes) {
                if (!isset($attributes['default_avatar_id'])) return null;
                return 'https://avatars.yandex.net/get-yapic/' . $attributes['default_avatar_id'];
            },

            'linkUrl' => ['openid_identities', 0],
        ];
    }
}
