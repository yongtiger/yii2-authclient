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
 * Microsoft Live OAuth2
 *
 * In order to use Microsoft Live OAuth2 you must register your application at <https://account.live.com/developers/applications>
 *
 * Note: Be sure to add `User.Read` and `User.ReadAll.Basic` in `Microsoft Graph Permissions`.
 *
 * Note: The redirect URI for web apps and services must begin with the scheme `https`. That means you cannot use `localhost` or `127.0.0.1` for testing!
 *
 * @see https://docs.microsoft.com/en-us/azure/active-directory/active-directory-v2-limitations#restrictions-on-redirect-uris
 *
 * Sample `Redirect URIs`: 
 * `https://yourdomain.com/1_oauth/1_oauth/frontend/web/index.php (OK)
 * `http://yourdomain.com/1_oauth/1_oauth/frontend/web/index.php (WRONG!)
 * `https://www.yourdomain.com/1_oauth/1_oauth/frontend/web/index.php (OK)
 * `https://yourdomain.com/1_oauth/1_oauth/frontend/web/index.php?r=site/auth (WRONG!)
 *
 * Example application configuration:
 *
 * ```php
 * 'components' => [
 *     'authClientCollection' => [
 *         'class' => 'yii\authclient\Collection',
 *         'clients' => [
 *             'live' => [
 *                 'class' => 'yii\authclient\clients\Live',
 *                 'clientId' => 'live_client_id',
 *                 'clientSecret' => 'live_client_secret',
 *                 ///'scope' => 'wl.basic wl.emails wl.contacts_emails wl.signin',
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
       "id": "ab30d9e58b344caa", 
       "name": "tiger yang", 
       "first_name": "tiger", 
       "last_name": "yang", 
       "link": "https://profile.live.com/", 
       "gender": null, 
       "emails": {
          "preferred": "tigeryang.brainbook@outlook.com", 
          "account": "tigeryang.brainbook@outlook.com", 
          "personal": null, 
          "business": null
       }, 
       "locale": "en_US", 
       "updated_time": "2017-01-13T05:24:39+0000"
    }
 * ```
 * 
 * getUserAttributes():
 *
 * ```php
    Array
    (
        [id] => ab30d9e58b344caa
        [name] => tiger yang
        [first_name] => tiger
        [last_name] => yang
        [link] => https://profile.live.com/
        [gender] => 
        [emails] => Array
            (
                [preferred] => tigeryang.brainbook@outlook.com
                [account] => tigeryang.brainbook@outlook.com
                [personal] => 
                [business] => 
            )

        [locale] => zh_CN
        [updated_time] => 2017-01-15T07:57:30+0000
        [uid] => ab30d9e58b344caa
        [email] => tigeryang.brainbook@outlook.com
        [fullname] => tiger yang
        [firstname] => tiger
        [lastname] => yang
        [language] => zh_CN
        [avatarUrl] => https://apis.live.net/v5.0/ab30d9e58b344caa/picture?type=large
        [linkUrl] => https://profile.live.com/cid-ab30d9e58b344caa
    )
 * ```
 *
 * [REFERENCES]
 *
 * @see https://account.live.com/developers/applications
 * @see http://msdn.microsoft.com/en-us/library/live/hh243647.aspx
 * @see https://msdn.microsoft.com/en-us/library/hh243648.aspx#user
 */
class Live extends \yii\authclient\clients\Live implements IAuth
{
    use ClientTrait;

    /**
     * @inheritdoc
     */
    protected function defaultViewOptions() {
        return [
            'popupWidth' => 480,
            'popupHeight' => 480,
        ];
    }
    /**
     * @inheritdoc
     */
    protected function defaultNormalizeUserAttributeMap() {
        return [
            'uid' => 'id',

            'email' => ['emails', 'account'],

            'fullname' => 'name',

            'firstname' => 'first_name',

            'lastname' => 'last_name',

            'gender' => function ($attributes) {
                if (!isset($attributes['gender'])) return null;   ///why always null???
                return $attributes['gender'] == 'M' ? static::GENDER_MALE : ($attributes['gender'] == 'F' ? static::GENDER_FEMALE : null);
            },

            'language' => 'locale',

            ///@see http://stackoverflow.com/questions/8305521/get-profile-picture-from-windows-live
            ///e.g. `https://apis.live.net/v5.0/ab30d9e58b344caa/picture?type=large`
            ///
            ///Note: To redirect a GET call to the URL of a user's picture, you can call /me/picture or /USER_ID/picture.
            ///@see http://stackoverflow.com/questions/11900012/how-to-get-profile-pictures-of-each-contact
            'avatarUrl' => function ($attributes) {
                return $this->apiBaseUrl . '/' . $attributes['id'] .'/picture?type=large';
            },

            ///https://profile.live.com/cid-ab30d9e58b344caa/
            'linkUrl' => function ($attributes) {
                if (!isset($attributes['link'])) return null;
                return 'https://profile.live.com/cid-' . $attributes['id'] ;
            },
        ];
    }
}
