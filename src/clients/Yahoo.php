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
 * Yahoo OAuth2
 *
 * In order to use Yahoo OAuth2 you must register your Yahoo developer account and create your application at <https://developer.yahoo.com>, 
 * and then create your Yahoo applicaion at <https://developer.yahoo.com/apps/>.
 * 
 * Note: Be sure to select `Read/Write Public and Private` to get user email!
 *
 * Note: `Callback Domain` can NOT be `localhost`! but `127.0.0.1` works for testing, e.g. `127.0.0.1`.
 *
 * Sample `Callback Domain`: 
 * `localhost` (WRONG!)
 * `127.0.0.1` (OK)
 * 
 * Example Yii2 application configuration:
 *
 * ```php
 * 'components' => [
 *     'authClientCollection' => [
 *         'class' => 'yii\authclient\Collection',
 *         'clients' => [
 *             'yahoo' => [
 *                 'class' => 'yii\authclient\clients\Yahoo',
 *                 'clientId' => 'yahoo_client_id',
 *                 'clientSecret' => 'yahoo_client_secret',
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
 * {"profile":{"guid":"IQEUSXXTPBMUFGMWXIJOT3HDII","addresses":[{"city":"","country":"US","current":true,"id":1,"postalCode":"","state":"","street":"","type":"HOME"}],"ageCategory":"A","created":"2017-01-13T02:29:31Z","emails":[{"handle":"yongtiger@yahoo.com","id":2,"primary":false,"type":"HOME"}],"familyName":"yong","gender":"M","givenName":"tiger","image":{"height":192,"imageUrl":"https://s.yimg.com/sf/modern/images/default_user_profile_pic_192.png","size":"192x192","width":192},"intl":"us","jurisdiction":"us","lang":"en-US","memberSince":"2017-01-12T22:41:07Z","migrationSource":1,"nickname":"tiger","notStored":true,"nux":"0","phones":[{"id":10,"number":"1-8315358975","type":"MOBILE","verified":true}],"profileMode":"PUBLIC","profileStatus":"ACTIVE","profileUrl":"http://profile.yahoo.com/IQEUSXXTPBMUFGMWXIJOT3HDII","isConnected":true,"profileHidden":false,"bdRestricted":true,"profilePermission":"PRIVATE","uri":"https://social.yahooapis.com/v1/user/IQEUSXXTPBMUFGMWXIJOT3HDII/profile"}}
 * ```
 *
 * getUserAttributes():
 *
 * ```php
    Array
    (
        [guid] => IQEUSXXTPBMUFGMWXIJOT3HDII
        [addresses] => Array
            (
                [0] => Array
                    (
                        [city] => 
                        [country] => US
                        [current] => 1
                        [id] => 1
                        [postalCode] => 
                        [state] => 
                        [street] => 
                        [type] => HOME
                    )

            )

        [ageCategory] => A
        [created] => 2017-01-13T02:29:31Z
        [emails] => Array
            (
                [0] => Array
                    (
                        [handle] => yongtiger@yahoo.com
                        [id] => 2
                        [primary] => 
                        [type] => HOME
                    )

            )

        [familyName] => yong
        [gender] => M
        [givenName] => tiger
        [image] => Array
            (
                [height] => 192
                [imageUrl] => https://s.yimg.com/sf/modern/images/default_user_profile_pic_192.png
                [size] => 192x192
                [width] => 192
            )

        [intl] => us
        [jurisdiction] => us
        [lang] => en-US
        [memberSince] => 2017-01-12T22:41:07Z
        [migrationSource] => 1
        [nickname] => tiger
        [notStored] => 1
        [nux] => 0
        [phones] => Array
            (
                [0] => Array
                    (
                        [id] => 10
                        [number] => 1-8315358975
                        [type] => MOBILE
                        [verified] => 1
                    )

            )

        [profileMode] => PUBLIC
        [profileStatus] => ACTIVE
        [profileUrl] => http://profile.yahoo.com/IQEUSXXTPBMUFGMWXIJOT3HDII
        [isConnected] => 1
        [profileHidden] => 
        [bdRestricted] => 1
        [profilePermission] => PRIVATE
        [uri] => https://social.yahooapis.com/v1/user/IQEUSXXTPBMUFGMWXIJOT3HDII/profile
        [fullname] => tiger yong
        [email] => yongtiger@yahoo.com
        [avatarUrl] => https://s.yimg.com/sf/modern/images/default_user_profile_pic_192.png
    )
 * ```
 *
 * [REFERENCES]
 *
 * GET returns the profile of a given {guid}:
 *
 * ```
 * https://social.yahooapis.com/v1/user/{guid}/profile
 * ```
 * 
 * @see https://developer.yahoo.com/social/rest_api_guide/extended-profile-resource.html
 *
 * Sample Request Header:
 * 
 * ```
 * GET https://social.yahooapis.com/v1/user/abcdef123/profile?format=json
 * ```
 * 
 * @see https://developer.yahoo.com/social/rest_api_guide/extended-profile-resource.html#profile-json_response 
 * 
 * ```
 * curl -H "Authorization: Bearer YOURAUTHTOKEN" https://social.yahooapis.com/v1/user/GUID/profile?format=json
 * ```
 *
 * Other references:
 *
 * @see https://developer.yahoo.com/
 * @see https://developer.yahoo.com/oauth/
 * @see https://developer.yahoo.com/oauth2/guide/
 * @see https://developer.yahoo.com/social/rest_api_guide/
 */
class Yahoo extends OAuth2 implements IAuth
{
    /**
     * @inheritdoc
     */
    public $authUrl = 'https://api.login.yahoo.com/oauth2/request_auth';
    /**
     * @inheritdoc
     */
    public $tokenUrl = 'https://api.login.yahoo.com/oauth2/get_token';
    /**
     * @inheritdoc
     */
    public $apiBaseUrl = 'https://social.yahooapis.com/v1';

    /**
     * @inheritdoc
     */
    protected function defaultName() {
        return 'yahoo';
    }

    /**
     * @inheritdoc
     */
    protected function defaultTitle() {
        return 'Yahoo';
    }

    /**
     * @inheritdoc
     */
    protected function defaultViewOptions() {
        return [
            'popupWidth' => 480,
            'popupHeight' => 680,   ///for when you only choose the profile scope during creating Yahoo application
            // 'popupHeight' => 840,   ///for all scopes
        ];
    }

    /**
     * @inheritdoc
     */
    protected function defaultNormalizeUserAttributeMap() {
        return [
            ///Yahoo register a new account with Email instead of username, also needed first name and last name.
            ///So we generate the fullname with givenName and familyName, 
            ///according to the above `EXAMPLE JSON RESPONSE BODY FOR GET`: `[givenName] => Tiger` and `[familyName] => Yong`.
            'fullname' => function ($attributes) {
                return $attributes['givenName'] . ' ' . $attributes['familyName'];
            },
            'email' => ['emails', 0, 'handle'],      ///`[emails][0][handle] => yongtiger@yahoo.com`
            'avatarUrl' => ['image', 'imageUrl'],    ///`[image][imageUrl] => https://s.yimg.com/sf/modern/images/default_user_profile_pic_192.png`
        ];
    }

    /**
     * @inheritdoc
     */
    protected function initUserAttributes() {
        $guid = $this->api('me/guid?format=json', 'GET');
        $profile = $this->api('user/'. $guid['guid']['value'] .'/profile?format=json', 'GET');
        return $profile['profile'];
    }

    /**
     * @inheritdoc
     */
    public function getEmail()
    {
        return $this->getUserAttributes()['email'] ? : null;
    }

    /**
     * @inheritdoc
     */
    public function getFullName()
    {
        return $this->getUserAttributes()['fullname'] ? : null;
    }

    /**
     * @inheritdoc
     */
    public function getAvatarUrl()
    {
        return $this->getUserAttributes()['avatarUrl'] ? : null;
    }
}