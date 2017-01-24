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
 * Note: Authorization `Callback URL` can contain `localhost` or `127.0.0.1` for testing.
 *
 * Sample `Callback URL`: 
 *
 * `http://localhost/1_oauth/frontend/web/index.php/site/auth?authclient=yandex` (OK)
 * `http://localhost/1_oauth/frontend/web/index.php?r=site/auth` (WRONG!)
 * `http://localhost/1_oauth/frontend/web/index.php?r=site/auth&authclient=yandex` (WRONG!)
 * `http://localhost/1_oauth/frontend/web/index.php` (WRONG!)
 * `http://localhost` (WRONG!)
 * `http://127.0.0.1/1_oauth/frontend/web/index.php/site/auth?authclient=yandex` (OK)
 * `http://127.0.0.1/1_oauth/frontend/web/index.php?r=site/auth` (WRONG!)
 * `http://127.0.0.1/1_oauth/frontend/web/index.php?r=site/auth&authclient=yandex` (WRONG!)
 * `http://127.0.0.1/1_oauth/frontend/web/index.php` (WRONG!)
 * `http://127.0.0.1` (WRONG!)
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
 * [Usage]
 * 
 * public function connectCallback(\yongtiger\authclient\clients\IAuth $client)
 * {
 *     ///Uncomment below to see which attributes you get back.
 *     ///First time to call `getUserAttributes()`, only return the basic attrabutes info for login, such as openid.
 *     echo "<pre>";print_r($client->getUserAttributes());echo "</pre>";
 *     echo "<pre>";print_r($client->provider);echo "</pre>";
 *     echo "<pre>";print_r($client->openid);echo "</pre>";
 *     ///If `$attribute` is not exist in the basic user attrabutes, call `initUserInfoAttributes()` and merge the results into the basic user attrabutes.
 *     echo "<pre>";print_r($client->email);echo "</pre>";
 *     ///After calling `initUserInfoAttributes()`, will return all user attrabutes.
 *     echo "<pre>";print_r($client->getUserAttributes());echo "</pre>";
 *     echo "<pre>";print_r($client->fullName);echo "</pre>";
 *     echo "<pre>";print_r($client->firstName);echo "</pre>";
 *     echo "<pre>";print_r($client->lastName);echo "</pre>";
 *     echo "<pre>";print_r($client->language);echo "</pre>";
 *     echo "<pre>";print_r($client->gender);echo "</pre>";
 *     echo "<pre>";print_r($client->avatarUrl);echo "</pre>";
 *     echo "<pre>";print_r($client->linkUrl);echo "</pre>";
 *     exit;
 *     // ...
 * }
 *
 * [EXAMPLE RESPONSE]
 *
 * Authorization URL:
 *
 * ```
 * https://passport.yandex.ru/auth?retpath=https%3A%2F%2Foauth.yandex.ru%2Fauthorize%3Fclient_id%3D6c630052d9d7452c802963caf10cc835%26response_type%3Dcode%26redirect_uri%3Dhttp%253A%252F%252Flocalhost%252F1_oauth%252Ffrontend%252Fweb%252Findex.php%252Fsite%252Fauth%253Fauthclient%253Dyandex%26xoauth_displayname%3DMy%2520Application%26state%3D39d347d5a47596bd312e855477ab647e340ca530cb001681544e0c361fb71b4b&origin=oauth
 * ```
 *
 * AccessToken Request:
 *
 * ```
 * https://oauth.yandex.ru/token
 * ```
 *
 * AccessToken Response:
 *
 * ```
 *  {"token_type": "bearer", "access_token": "AQAAAAAbOCbnAAP8zD0pqLQlsEMMllmC4S9z0OA", "expires_in": 31378644, "refresh_token": "1:xWzz4Poo_Zv_QLj3:qyi7O5In1vUSBeA0ChOqWKhqribY9EDlbHJozIMwe6CzawU_WsKd:jtgPwIitnQEDeZC6adyA2A"}
 * ```
 *
 * Request of `initUserAttributes()`:
 *
 * ```
 * https://login.yandex.ru/info?format=json&oauth_token=AQAAAAAbOCbnAAP8zD0pqLQlsEMMllmC4S9z0OA
 * ```
 *
 * Response of `initUserAttributes()`:
 *
 * ```
 * {"login": "yong.tiger", "id": "456664807"}
 * ```
 *
 * ```php
 * Array
 * (
 *     [login] => yong.tiger
 *     [id] => 456664807
 *     [provider] => yandex
 *     [openid] => 456664807
 *     [fullname] => yong.tiger
 *     [gender] => 
 *     [avatarUrl] => 
 * )
 * ```
 *
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
            'provider' => $this->defaultName(),
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
