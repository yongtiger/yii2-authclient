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
 * Facebook OAuth2
 *
 * In order to use Facebook OAuth2 you must add your Facebook Login product at <https://developers.facebook.com/apps>.
 *
 * Note:  Authorization `Callback URL` can contain `localhost` or `127.0.0.1` for testing.
 *
 * Sample `Callback URL`: 
 * `http://localhost/1_oauth/frontend/web/index.php?r=site/auth` (OK)
 * `http://localhost/1_oauth/frontend/web/index.php?r=site%2Fauth` (OK)
 * `http://localhost/1_oauth/frontend/web/index.php?r=site/auth&authclient=facebook` (OK)
 * `http://localhost/1_oauth/frontend/web/index.php?r=site%2Fauth&authclient=facebook` (OK)
 * `http://localhost/1_oauth/frontend/web/index.php?r=site%2Fauth%26authclient=facebook` (WRONG!)
 * `http://localhost/1_oauth/frontend/web/?r=site/auth` (WRONG!)
 * `http://localhost/1_oauth/frontend/web/?r=site%2Fauth` (WRONG!)
 * `http://localhost/1_oauth/frontend/web/?r=site/auth&authclient=facebook` (WRONG!)
 * `http://localhost/1_oauth/frontend/web/?r=site%2Fauth&authclient=facebook` (WRONG!)
 * `http://localhost/1_oauth/frontend/web/?r=site%2Fauth%26authclient=facebook` (WRONG!)
 * 
 * Example application configuration:
 *
 * ```php
 * 'components' => [
 *     'authClientCollection' => [
 *         'class' => 'yii\authclient\Collection',
 *         'clients' => [
 *             'facebook' => [
 *                 'class' => 'yii\authclient\clients\Facebook',
 *                 'clientId' => 'facebook_client_id',
 *                 'clientSecret' => 'facebook_client_secret',
 *                 ///'scope' => 'email',
 *             ],
 *         ],
 *     ]
 *     ...
 * ]
 * ```
 * @see http://www.yiiframework.com/doc-2.0/yii-authclient-clients-facebook.html
 *
 * [EXAMPLE JSON RESPONSE BODY FOR GET]
 * 
 * `$responseContent` at `/vendor/yiisoft/yii2-httpclient/StreamTransport.php`:
 *
 * ```
 * {"name":"Tiger Yong","email":"tigeryang.brainbook
 \u0040outlook.com","first_name":"Tiger","last_name":"Yong","picture":{"data":
 {"is_silhouette":true,"url":"https:\/\/scontent.xx.fbcdn.net\/v\/t1.0-1\/c15.0.50.50\/p50x50\/
 10354686_10150004552801856_220367501106153455_n.jpg?
 oh=978df650af5b925f321fe4050af2869f&oe=5911542F"}},"gender":"male","age_range":{"min":
 21},"link":"https:\/\/www.facebook.com\/app_scoped_user_id\/
 123618604810465\/","locale":"en_US","timezone":
 8,"updated_time":"2017-01-13T21:40:58+0000","verified":true,"id":"123618604810465"}
 * ```
 *
 * getUserAttributes():
 *
 * ```php
    Array
    (
        [name] => Tiger Yong
        [first_name] => Tiger
        [last_name] => Yong
        [gender] => 1
        [age_range] => Array
            (
                [min] => 21
            )

        [link] => https://www.facebook.com/app_scoped_user_id/123618604810465/
        [locale] => en_US
        [picture] => Array
            (
                [data] => Array
                    (
                        [is_silhouette] => 1
                        [url] => https://scontent.xx.fbcdn.net/v/t1.0-1/c15.0.50.50/p50x50/10354686_10150004552801856_220367501106153455_n.jpg?oh=978df650af5b925f321fe4050af2869f&oe=5911542F
                    )

            )

        [timezone] => 8
        [updated_time] => 2017-01-13T21:40:58+0000
        [verified] => 1
        [email] => tigeryang.brainbook@outlook.com
        [id] => 123618604810465
        [uid] => 123618604810465
        [fullname] => Tiger Yong
        [firstname] => Tiger
        [lastname] => Yong
        [language] => en_US
        [avatarUrl] => https://scontent.xx.fbcdn.net/v/t1.0-1/c15.0.50.50/p50x50/10354686_10150004552801856_220367501106153455_n.jpg?oh=978df650af5b925f321fe4050af2869f&oe=5911542F
        [linkUrl] => https://www.facebook.com/app_scoped_user_id/123618604810465/
    )
 * ```
 *
 * [REFERENCES]
 *
 * @see https://developers.facebook.com/apps
 * @see http://developers.facebook.com/docs/reference/api
 * @see http://www.yiiframework.com/doc-2.0/yii-authclient-clients-facebook.html
 */
class Facebook extends \yii\authclient\clients\Facebook implements IAuth
{
    use ClientTrait;

    /**
     * @var array list of attribute names, which should be requested from API to initialize user attributes.
     * @since 2.0.5
     *
     * Override the property of `attributeNames` to get other profile attributes.
     * @see https://developers.facebook.com/docs/facebook-login/permissions/#reference-public_profile
     */
    public $attributeNames = [
        'name', 'first_name', 'last_name', 'gender', 'cover', 'age_range', 'link', 'locale', 'picture', 'timezone', 'updated_time', 'verified',
        'email',
    ];

    /**
     * @inheritdoc
     */
    protected function defaultViewOptions() {
        return [
            'popupWidth' => 1000,
            'popupHeight' => 720,
        ];
    }

    /**
     * @inheritdoc
     */
    protected function defaultNormalizeUserAttributeMap() {
        return [
            'openid' => 'id',

            'fullname' => 'name',

            'firstname' => 'first_name',

            'lastname' => 'last_name',

            'gender' => function ($attributes) {
                if (!isset($attributes['gender'])) return null;
                return $attributes['gender'] == 'male' ? static::GENDER_MALE : ($attributes['gender'] == 'female' ? static::GENDER_FEMALE : null);
            },

            'language' => 'locale',

            'avatarUrl' => ['picture', 'data', 'url'],

            'linkUrl' => 'link',
        ];
    }
}
