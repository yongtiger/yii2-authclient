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
 * VKontakte OAuth2
 *
 * In order to use VKontakte OAuth2 you must register your application at <http://vk.com/editapp?act=create>.
 *
 * Note: Authorization `Callback URL` can contain `localhost` or `127.0.0.1` for testing, but CANNOT contain `?` or `&`.
 *
 * Sample `Callback URL`:
 *
 * `http://localhost/1_oauth/frontend/web/index.php/site/auth` (OK)
 * `http://localhost/1_oauth/frontend/web/index.php/site/auth?authclient=vkontakte` (WRONG!)
 * `http://localhost/1_oauth/frontend/web/index.php??r=site/auth` (WRONG!)
 * `http://localhost/1_oauth/frontend/web/index.php` (WRONG!)
 * `http://localhost/1_oauth/frontend/web` (WRONG!)
 * `http://localhost` (WRONG!)
 * `http://127.0.0.1/1_oauth/frontend/web/index.php/site/auth` (OK)
 * `http://127.0.0.1/1_oauth/frontend/web/index.php/site/auth?authclient=vkontakte` (WRONG!)
 * `http://127.0.0.1/1_oauth/frontend/web/index.php??r=site/auth` (WRONG!)
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
 *             'vkontakte' => [
 *                 'class' => 'yii\authclient\clients\VKontakte',
 *                 'clientId' => 'vkontakte_client_id',
 *                 'clientSecret' => 'vkontakte_client_secret',
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
 * https://oauth.vk.com/authorize?client_id=5827650&response_type=code&redirect_uri=http%3A%2F%2Flocalhost%2F1_oauth%2Ffrontend%2Fweb%2Findex.php%3Fr%3Dsite%252Fauth%26authclient%3Dvkontakte&xoauth_displayname=My%20Application&state=6f98fbde79cc28f4b4044f75b5e53f7e84c95b08393ddedfc177b402c9d78108
 * ```
 *
 * AccessToken Request:
 *
 * ```
 * https://oauth.vk.com/access_token
 * ```
 *
 * AccessToken Response:
 *
 * ```
 * {"access_token":"c0569c13ca037b92385e0b735beecc89087f79fd03ea9a296c8ff0b5649994d802f78fab49a7afbfa7492","expires_in":86395,"user_id":407891976}
 * ```
 *
 * Request of `initUserAttributes()`:
 *
 * ```
 * https://api.vk.com/method/users.get.json?fields=uid%2Cfirst_name%2Clast_name%2Cnickname%2Cscreen_name%2Csex%2Cbdate%2Ccity%2Ccountry%2Ctimezone%2Cphoto&uids=407891976&access_token=c0569c13ca037b92385e0b735beecc89087f79fd03ea9a296c8ff0b5649994d802f78fab49a7afbfa7492
 * ```
 *
 * Response of `initUserAttributes()`:
 *
 * ```
 * {"response":[{"uid":
 407891976,"first_name":"Tiger","last_name":"Yong","sex":2,"nickname":"","screen_name":"id407891976","bdate":"9.1.1971","city":0,"country":0,"timezone":-8,"photo":"http:\/\/vk.com\/images\/camera_50.png"}]}
 * ```
 *
 * ```php
 * Array
 * (
 *     [user_id] => 407891976
 *     [uid] => 407891976
 *     [first_name] => Tiger
 *     [last_name] => Yong
 *     [sex] => 2
 *     [nickname] => 
 *     [screen_name] => id407891976
 *     [bdate] => 9.1.1971
 *     [city] => 0
 *     [country] => 0
 *     [timezone] => -8
 *     [photo] => http://vk.com/images/camera_50.png
 *     [provider] => vkontakte
 *     [openid] => 407891976
 *     [email] => 
 *     [fullname] => Tiger Yong
 *     [firstname] => Tiger
 *     [lastname] => Yong
 *     [gender] => 1
 *     [avatar] => http://vk.com/images/camera_50.png
 *     [link] => https://vk.com/id407891976
 * )
 * ```
 *
 * [REFERENCES]
 *
 * @see http://vk.com/editapp?act=create
 * @see http://vk.com/developers.php?oid=-1&p=users.get
 * @see https://vk.com/dev/manuals
 */
class VKontakte extends \yii\authclient\clients\VKontakte implements IAuth
{
    use ClientTrait;

    /**
     * @inheritdoc
     */
    protected function defaultViewOptions() {
        return [
            'popupWidth' => 760,
            'popupHeight' => 560,
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
            'email' => function ($attributes) {
                return $this->getAccessToken()->getParam('email');
            },
            ///VKontakte register a new account with Email instead of username, also needed first name and last name.
            ///So we generate the fullname with givenName and familyName, 
            ///according to the above `EXAMPLE JSON RESPONSE BODY FOR GET`: `[givenName] => Tiger` and `[familyName] => Yong`.
            'fullname' => function ($attributes) {
                if (!isset($attributes['first_name']) || !isset($attributes['last_name'])) return null;
                return $attributes['first_name'] . ' ' . $attributes['last_name'];
            },
            'firstname' => 'first_name',
            'lastname' => 'last_name',
            'gender' => function ($attributes) {
                if (!isset($attributes['sex'])) return null;
                return $attributes['sex'] == '2' ? static::GENDER_MALE : ($attributes['sex'] == '1' ? static::GENDER_FEMALE : null);
            },
            'avatar' => 'photo',
            'link' => function ($attributes) {
                if (!isset($attributes['screen_name'])) return null;
                return 'https://vk.com/' . $attributes['screen_name'];
            },
        ];
    }
}
