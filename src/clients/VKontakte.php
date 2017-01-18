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
 * Note:  Authorization `Callback URL` can contain `localhost` or `127.0.0.1` for testing.
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
 *     ...
 * ]
 * ```
 *
 * [EXAMPLE JSON RESPONSE BODY FOR GET]
 * 
 * `$responseContent` at `/vendor/yiisoft/yii2-httpclient/StreamTransport.php`:
 *
 * ```
 *  {"response":[{"uid": 407891976,"first_name":"Tiger","last_name":"Yong","sex": 2,"nickname":"","screen_name":"id407891976","bdate":"9.1.1971","city":0,"country":0,"timezone": 8,"photo":"http:\/\/vk.com\/images\/camera_50.png"}]}
 * ```
 *
 * getUserAttributes():
 *
 * ```php
    Array
    (
        [user_id] => 407891976
        [uid] => 407891976
        [first_name] => Tiger
        [last_name] => Yong
        [sex] => 2
        [nickname] => 
        [screen_name] => id407891976
        [bdate] => 9.1.1971
        [city] => 0
        [country] => 0
        [timezone] => 8
        [photo] => http://vk.com/images/camera_50.png
        [openid] => 407891976
        [fullname] => Tiger Yong
        [firstname] => Tiger
        [lastname] => Yong
        [gender] => 1
        [avatarUrl] => http://vk.com/images/camera_50.png
        [linkUrl] => https://vk.com/id407891976
    )
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

            'avatarUrl' => 'photo',

            'linkUrl' => function ($attributes) {
                if (!isset($attributes['screen_name'])) return null;
                return 'https://vk.com/' . $attributes['screen_name'];
            },
        ];
    }
}
