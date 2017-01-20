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
 *
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
 *                 /// 'scope' => 'openid'
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
 * https://login.yahoo.com/config/login?.done=https%3A%2F%2Fapi.login.yahoo.com%2Foauth2%2Frequest_auth%3Fclient_id%3Ddj0yJmk9aVMxbnRvclppM1NmJmQ9WVdrOU4zZHlSMVJ3TXpJbWNHbzlNQS0tJnM9Y29uc3VtZXJzZWNyZXQmeD1iZg--%26response_type%3Dcode%26redirect_uri%3Dhttp%253A%252F%252F127.0.0.1%252F1_oauth%252Ffrontend%252Fweb%252Findex.php%253Fr%253Dsite%25252Fauth%2526authclient%253Dyahoo%26xoauth_displayname%3DMy%2520Application%26scope%3Dopenid%26state%3Da2f0ae6403667bf6350f4e094b5f98c7036b40511cee53758c9362bfa2095783%26.scrumb%3D0&.src=oauth2&.pd=c%3DmZmAFpe.2e7WuWzcHD2ZPYQ-%26ockey%3Ddj0yJmk9aVMxbnRvclppM1NmJmQ9WVdrOU4zZHlSMVJ3TXpJbWNHbzlNQS0tJnM9Y29uc3VtZXJzZWNyZXQmeD1iZg--&.crumb=wMvxlOacogX&redirect_uri=http%3A%2F%2F127.0.0.1%2F1_oauth%2Ffrontend%2Fweb%2Findex.php%3Fr%3Dsite%252Fauth%26authclient%3Dyahoo&state=a2f0ae6403667bf6350f4e094b5f98c7036b40511cee53758c9362bfa2095783
 * ```
 *
 * AccessToken Request:
 *
 * ```
 * https://api.login.yahoo.com/oauth2/get_token
 * ```
 *
 * AccessToken Response:
 *
 * ```
 * {"access_token":"pBiOUpWetFms8oB9pOpg_Uir_il2qtWYObOHsCLuP47SCMY5d0drS.f42d5LZwHOie1Fd
 *  d0orMtVtFZdz7VP_VYkRxa2kmWBWoCbHWXm.xFR71SvG_1tSXKiYvm0.L1mBtqoE0iMLDHG9XVJrP.eZyq
 *  arcSWy5nZKpU2.xchUgiacLqj6TuZ7TvnVWiABZW570oMdppmaU4sSiXfL0Mx1OahOK668_qAgOjBp2PWi
 *  ZWcbFouguWeuyeD6G_E0yLdC76w1V0vnsCc68tWOIJgsp7LcsPoPliejwINCQyzEGCgrToREDaFuo89I7nPg
 *  8XEUOvJTRSKgT_Jh_vU5IUJ9dLNIxlU15Q4e1sxQCGzD3p3.6mOhGxpmelVFphON2tHiW3viBav4lZkqLh4F
 *  W_uPeACeAfsedxME7GZq0V7b7SPsXnntv0lxZkBCzZ.unNQTVQQuLHiMzhpinN7ujkD_t9XyOvRfyf9V6U.
 *  9AsNxl7Vau5R_zTyiWQBiNir..XptlCwZ8WMhSvsS.
 *  7kHAQxd..m3RBBmDjT1SDD55tET7.qtVhTLd1e5VLx.uokqPFrYd4I2.QNSOITgrEClqNByUn41LNi_cn2YWF
 *  bi3xd9TB5BR2sCOgFD54yrv_jgbY9PuEOR7fMVOCBMcm78Xx8v17w6EHZQThhq..J.N8Po6e1gLTYYAOR_
 *  UCtGc6qdAv1CJctTpvqZI0FopsLRbXze1i5AvZYJuZiudarnv3IWQPcNYibJ3Cm.bQ.Jx1CWJ3Mw3jf_oAItooV
 *  0_48ax12.ET7CE1W8pCNtOQkIjPb6iI7P7_pKYDBCnygkNGGVmrIqGrw6tND","refresh_token":"ABJdgVg.
 *  9SYS5SSOxf9q_mN6CNeZvq5q.KQ5NXt_90hCiQfAJg--","expires_in":
 *  3600,"token_type":"bearer","xoauth_yahoo_guid":"IQEUSXXTPBMUFGMWXIJOT3HDII","id_token":"eyJh
 *  bGciOiJFUzI1NiIsImtpZCI6IjM0NjZkNTFmN2RkMGM3ODA1NjU2ODhjMTgzOTIxODE2YzQ1ODg5YWQif
 *  Q.eyJhdF9oYXNoIjoiWU9nWHJibkZlMS85ZUpWRHduOW5aZz09Iiwic3ViIjoiSVFFVVNYWFRQQk1VRkdN
 *  V1hJSk9UM0hESUkiLCJhdWQiOiJkajB5Sm1rOWFWTXhiblJ2Y2xwcE0xTm1KbVE5V1Zkck9VNHpaSGxTTV
 *  ZKM1RYcEpiV05IYnpsTlFTMHRKbk05WTI5dWMzVnRaWEp6WldOeVpYUW1lRDFpWmctLSIsImF1dGhfd
 *  GltZSI6MTQ4NDg3MjcyMCwiaXNzIjoiaHR0cHM6Ly9hcGkubG9naW4ueWFob28uY29tIiwic2Vzc2lvbl9leH
 *  AiOjE0ODYwODIzMjAsImV4cCI6MTQ4NDg3NjU3OCwiaWF0IjoxNDg0ODcyOTc4LCJub25jZSI6IiJ9.WIUP
 *  MpkePEFBdKOnqjVx-hrYYDholR1MFwyhB3eP-
 *  h6NnejdyAJJRrdsdPI1eFsN9mo-3zQLG8vR6o_ROQdP7Q"}
 * ```
 *
 * Request of `initUserAttributes()`:
 *
 * ```
 * https://social.yahooapis.com/v1/me/guid?format=json&access_token=pBiOUpWetFms8oB9pOpg_Uir_il2qtWYObOHsCLuP47SCMY5d0drS.f42d5LZwHOie1Fdd0orMtVtFZdz7VP_VYkRxa2kmWBWoCbHWXm.xFR71SvG_1tSXKiYvm0.L1mBtqoE0iMLDHG9XVJrP.eZyqarcSWy5nZKpU2.xchUgiacLqj6TuZ7TvnVWiABZW570oMdppmaU4sSiXfL0Mx1OahOK668_qAgOjBp2PWiZWcbFouguWeuyeD6G_E0yLdC76w1V0vnsCc68tWOIJgsp7LcsPoPliejwINCQyzEGCgrToREDaFuo89I7nPg8XEUOvJTRSKgT_Jh_vU5IUJ9dLNIxlU15Q4e1sxQCGzD3p3.6mOhGxpmelVFphON2tHiW3viBav4lZkqLh4FW_uPeACeAfsedxME7GZq0V7b7SPsXnntv0lxZkBCzZ.unNQTVQQuLHiMzhpinN7ujkD_t9XyOvRfyf9V6U.9AsNxl7Vau5R_zTyiWQBiNir..XptlCwZ8WMhSvsS.7kHAQxd..m3RBBmDjT1SDD55tET7.qtVhTLd1e5VLx.uokqPFrYd4I2.QNSOITgrEClqNByUn41LNi_cn2YWFbi3xd9TB5BR2sCOgFD54yrv_jgbY9PuEOR7fMVOCBMcm78Xx8v17w6EHZQThhq..J.N8Po6e1gLTYYAOR_UCtGc6qdAv1CJctTpvqZI0FopsLRbXze1i5AvZYJuZiudarnv3IWQPcNYibJ3Cm.bQ.Jx1CWJ3Mw3jf_oAItooV0_48ax12.ET7CE1W8pCNtOQkIjPb6iI7P7_pKYDBCnygkNGGVmrIqGrw6tND
 * ```
 *
 * Response of `initUserAttributes()`:
 *
 * ```
 *  {"guid":{"value":"IQEUSXXTPBMUFGMWXIJOT3HDII","uri":"https://social.yahooapis.com/v1/me/guid"}}
 * ```
 *
 * ```php
 * Array
 * (
 *     [guid] => Array
 *         (
 *             [value] => IQEUSXXTPBMUFGMWXIJOT3HDII
 *             [uri] => https://social.yahooapis.com/v1/me/guid
 *         )
 * 
 *     [openid] => IQEUSXXTPBMUFGMWXIJOT3HDII
 * )
 * ```
 *
 * Request of `initUserInfoAttributes()`:
 *
 * ```
 *  https://social.yahooapis.com/v1/user/IQEUSXXTPBMUFGMWXIJOT3HDII/profile?format=json&access_token=pBiOUpWetFms8oB9pOpg_Uir_il2qtWYObOHsCLuP47SCMY5d0drS.f42d5LZwHOie1Fdd0orMtVtFZdz7VP_VYkRxa2kmWBWoCbHWXm.xFR71SvG_1tSXKiYvm0.L1mBtqoE0iMLDHG9XVJrP.eZyqarcSWy5nZKpU2.xchUgiacLqj6TuZ7TvnVWiABZW570oMdppmaU4sSiXfL0Mx1OahOK668_qAgOjBp2PWiZWcbFouguWeuyeD6G_E0yLdC76w1V0vnsCc68tWOIJgsp7LcsPoPliejwINCQyzEGCgrToREDaFuo89I7nPg8XEUOvJTRSKgT_Jh_vU5IUJ9dLNIxlU15Q4e1sxQCGzD3p3.6mOhGxpmelVFphON2tHiW3viBav4lZkqLh4FW_uPeACeAfsedxME7GZq0V7b7SPsXnntv0lxZkBCzZ.unNQTVQQuLHiMzhpinN7ujkD_t9XyOvRfyf9V6U.9AsNxl7Vau5R_zTyiWQBiNir..XptlCwZ8WMhSvsS.7kHAQxd..m3RBBmDjT1SDD55tET7.qtVhTLd1e5VLx.uokqPFrYd4I2.QNSOITgrEClqNByUn41LNi_cn2YWFbi3xd9TB5BR2sCOgFD54yrv_jgbY9PuEOR7fMVOCBMcm78Xx8v17w6EHZQThhq..J.N8Po6e1gLTYYAOR_UCtGc6qdAv1CJctTpvqZI0FopsLRbXze1i5AvZYJuZiudarnv3IWQPcNYibJ3Cm.bQ.Jx1CWJ3Mw3jf_oAItooV0_48ax12.ET7CE1W8pCNtOQkIjPb6iI7P7_pKYDBCnygkNGGVmrIqGrw6tND
 * ```
 *
 * Response of `initUserInfoAttributes()`:
 *
 * ```
 * {"profile":
 *  {"guid":"IQEUSXXTPBMUFGMWXIJOT3HDII","ageCategory":"A","created":"2017-01-12T22:45:15Z","ema
 *  ils":[{"handle":"tigeryang.brainbook@outlook.com","id":10,"primary":true,"type":"HOME"},
 *  {"handle":"yongtiger@yahoo.com","id":2,"primary":false,"type":"HOME"},
 *  {"handle":"nas4qg6iqvzrglpjtolysnc2ve5zvqum7d2ugurv@yahoo.com","id":
 *  11,"primary":false,"type":"ALIAS"}],"familyName":"yong","gender":"M","givenName":"tiger","image":
 *  {"height":192,"imageUrl":"https://s.yimg.com/sf/modern/images/
 *  default_user_profile_pic_192.png","size":"192x192","width":192},"intl":"us","jurisdiction":"us","lang":"en-
 *  US","memberSince":"2017-01-12T22:41:07Z","migrationSource":
 *  1,"nickname":"tiger","notStored":true,"nux":"3","phones":[{"id":
 *  10,"number":"1-8315358975","type":"MOBILE","verified":true}],"profileMode":"PUBLIC","profileStatus":"
 *  ACTIVE","profileUrl":"http://profile.yahoo.com/
 *  IQEUSXXTPBMUFGMWXIJOT3HDII","updated":"2017-01-13T17:50:47Z","isConnected":true,"profileHidd
 *  en":false,"bdRestricted":true,"profilePermission":"PRIVATE","uri":"https://social.yahooapis.com/v1/user/
 *  IQEUSXXTPBMUFGMWXIJOT3HDII/profile","cache":true}}
 * ```
 *
 * ```php
 * Array
 * (
 *     [guid] => IQEUSXXTPBMUFGMWXIJOT3HDII
 *     [openid] => IQEUSXXTPBMUFGMWXIJOT3HDII
 *     [ageCategory] => A
 *     [created] => 2017-01-12T22:45:15Z
 *     [emails] => Array
 *         (
 *             [0] => Array
 *                 (
 *                     [handle] => tigeryang.brainbook@outlook.com
 *                     [id] => 10
 *                     [primary] => 1
 *                     [type] => HOME
 *                 )
 * 
 *             [1] => Array
 *                 (
 *                     [handle] => yongtiger@yahoo.com
 *                     [id] => 2
 *                     [primary] => 
 *                     [type] => HOME
 *                 )
 * 
 *             [2] => Array
 *                 (
 *                     [handle] => nas4qg6iqvzrglpjtolysnc2ve5zvqum7d2ugurv@yahoo.com
 *                     [id] => 11
 *                     [primary] => 
 *                     [type] => ALIAS
 *                 )
 * 
 *         )
 * 
 *     [familyName] => yong
 *     [gender] => 1
 *     [givenName] => tiger
 *     [image] => Array
 *         (
 *             [height] => 192
 *             [imageUrl] => https://s.yimg.com/sf/modern/images/default_user_profile_pic_192.png
 *             [size] => 192x192
 *             [width] => 192
 *         )
 * 
 *     [intl] => us
 *     [jurisdiction] => us
 *     [lang] => en-US
 *     [memberSince] => 2017-01-12T22:41:07Z
 *     [migrationSource] => 1
 *     [nickname] => tiger
 *     [notStored] => 1
 *     [nux] => 3
 *     [profileMode] => PUBLIC
 *     [profileStatus] => ACTIVE
 *     [profileUrl] => http://profile.yahoo.com/IQEUSXXTPBMUFGMWXIJOT3HDII
 *     [updated] => 2017-01-13T17:50:47Z
 *     [isConnected] => 1
 *     [profileHidden] => 
 *     [bdRestricted] => 1
 *     [profilePermission] => PRIVATE
 *     [uri] => https://social.yahooapis.com/v1/user/IQEUSXXTPBMUFGMWXIJOT3HDII/profile
 *     [cache] => 1
 *     [email] => tigeryang.brainbook@outlook.com
 *     [fullname] => tiger yong
 *     [firstname] => tiger
 *     [lastname] => yong
 *     [language] => en-US
 *     [avatarUrl] => https://s.yimg.com/sf/modern/images/default_user_profile_pic_192.png
 *     [linkUrl] => https://social.yahooapis.com/v1/user/IQEUSXXTPBMUFGMWXIJOT3HDII/profile
 * )

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
    use ClientTrait;

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
    public $scope = 'openid';

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
            'openid' => ['guid', 'value'],
        ];
    }

    /**
     * UserInfo Normalize User Attribute Map
     *
     * @return array
     */
    protected function userInfoNormalizeUserAttributeMap() {
        return [
            'email' => ['emails', 0, 'handle'],      ///`[emails][0][handle] => yongtiger@yahoo.com`
            ///Yahoo register a new account with Email instead of username, also needed first name and last name.
            ///So we generate the fullname with givenName and familyName, 
            ///according to the above `EXAMPLE JSON RESPONSE BODY FOR GET`: `[givenName] => Tiger` and `[familyName] => Yong`.
            'fullname' => function ($attributes) {
                if (!isset($attributes['givenName']) || !isset($attributes['familyName'])) return null;
                return $attributes['givenName'] . ' ' . $attributes['familyName'];
            },
            'firstname' => 'givenName',
            'lastname' => 'familyName',
            'gender' => function ($attributes) {
                if (!isset($attributes['gender'])) return null;
                return $attributes['gender'] == 'M' ? static::GENDER_MALE : ($attributes['gender'] == 'F' ? static::GENDER_FEMALE : null);
            },
            'language' => 'lang',
            'avatarUrl' => ['image', 'imageUrl'],
            'linkUrl' => 'uri',
        ];
    }

    /**
     * Get user openid and other basic information.
     *
     * @return array
     */
    protected function initUserAttributes() {
        return $this->api('me/guid?format=json', 'GET');
    }

    /**
     * Get extra user info.
     *
     * @return array
     */
    protected function initUserInfoAttributes()
    {
        return $this->api('user/'. $this->getUserAttributes()['openid'] .'/profile?format=json', 'GET')['profile'];
    }
}