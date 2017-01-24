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

use Yii;

/**
 * LinkedIn OAuth2
 *
 * In order to use linkedIn OAuth2 you must register your application at <https://www.linkedin.com/secure/developer>.
 *
 * Note: Authorization `Callback URL` can contain `localhost` or `127.0.0.1` for testing.
 *
 * Example application configuration:
 *
 * ```php
 * 'components' => [
 *     'authClientCollection' => [
 *         'class' => 'yii\authclient\Collection',
 *         'clients' => [
 *             'linkedin' => [
 *                 'class' => 'yii\authclient\clients\LinkedIn',
 *                 'clientId' => 'linkedin_client_id',
 *                 'clientSecret' => 'linkedin_client_secret',
 *             ],
 *         ],
 *     ]
 *     ...
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
 * https://www.linkedin.com/uas/oauth2/authorization?client_id=86ehrtbhkko1tl&response_type=code&redirect_uri=http%3A%2F%2Flocalhost%2F1_oauth%2Ffrontend%2Fweb%2Findex.php%3Fr%3Dsite%252Fauth%26authclient%3Dlinkedin&xoauth_displayname=My%20Application&scope=r_basicprofile%20r_emailaddress&state=c7affe27726abdef978ae0e766e41edd944316eee3136de4182ad3be0379ea79
 * ```
 *
 * AccessToken Request:
 *
 * ```
 * https://www.linkedin.com/uas/oauth2/accessToken
 * ```
 *
 * AccessToken Response:
 *
 * ```
 * {"access_token":"AQV0koY9kljbhrR3JhNyfXiEYkg_RakRi7nqUnuh9TNvc8wXJ3Uv6CRfzmcJzi4PT54iksff2JPKta4Rjk7byX65tAJX_OpRcp0UeKOrHkVPuMT-66E3yXmZD1hkAwjovadrTr6G-RGErJ1clbASd1HwMwUbN6CqBnSzknc8zSn-h_MTfQo","expires_in":5183999}
 * ```
 *
 * Request of `initUserAttributes()`:
 *
 * ```
 * https://api.linkedin.com/v1/people/~:(id,email-address,first-name,last-name,formatted-name,phonetic-first-name,phonetic-last-name,formatted-phonetic-name,public-profile-url,picture-url)?oauth2_access_token=AQV0koY9kljbhrR3JhNyfXiEYkg_RakRi7nqUnuh9TNvc8wXJ3Uv6CRfzmcJzi4PT54iksff2JPKta4Rjk7byX65tAJX_OpRcp0UeKOrHkVPuMT-66E3yXmZD1hkAwjovadrTr6G-RGErJ1clbASd1HwMwUbN6CqBnSzknc8zSn-h_MTfQo
 * ```
 *
 * Response of `initUserAttributes()`:
 *
 * ```
 * <?xml version="1.0" encoding="UTF-8" standalone="yes"?> <person> <id>09EXzPLzAo</id> <email-address>tigeryang.brainbook@outlook.com</email-address> <first-name>光</first-name> <last-name>杨</last-name> <formatted-name>杨光 (tiger yong)</formatted-name> <public-profile-url>https://www.linkedin.com/in/%E5%85%89-%E6%9D%A8-a91147133</public-profile-url> </person>
 * ```
 *
 * ```php
 * Array
 * (
 *     [id] => ab30d9e58b344caa
 *     [name] => tiger yang
 *     [first_name] => tiger
 *     [last_name] => yang
 *     [link] => https://profile.live.com/
 *     [birth_day] => 15
 *     [birth_month] => 8
 *     [birth_year] => 1970
 *     [work] => Array
 *         (
 *         )
 * 
 *     [gender] => 
 *     [emails] => Array
 *         (
 *             [preferred] => tigeryang.brainbook@outlook.com
 *             [account] => tigeryang.brainbook@outlook.com
 *             [personal] => 
 *             [business] => 
 *         )
 * 
 *     [locale] => zh_CN
 *     [updated_time] => 2017-01-16T19:31:18+0000
 *     [openid] => ab30d9e58b344caa
 *     [email] => tigeryang.brainbook@outlook.com
 *     [fullname] => tiger yang
 *     [firstname] => tiger
 *     [lastname] => yang
 *     [language] => zh_CN
 *     [avatarUrl] => https://apis.live.net/v5.0/ab30d9e58b344caa/picture?type=large
 *     [linkUrl] => https://profile.live.com/cid-ab30d9e58b344caa
 * )
 * ```
 *
 * [REFERENCES]
 *
 * @see http://developer.linkedin.com/documents/authentication
 * @see https://www.linkedin.com/secure/developer
 * @see http://developer.linkedin.com/apis
 * @see https://developer.linkedin.com/docs/fields/basic-profile
 */
class LinkedIn extends\yii\authclient\clients\LinkedIn implements IAuth
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
            'provider' => $this->defaultName,
            'openid' => 'id',
            'email' => 'email-address',
            'fullname' => 'formatted-name',
            'firstname' => 'first-name',
            'lastname' => 'last-name',
            'language' => 'lang',
            'avatarUrl' => 'picture-url',
            'linkUrl' => 'public-profile-url',
        ];
    }

    /**
     * @var array list of attribute names, which should be requested from API to initialize user attributes.
     * @since 2.0.4
     */
    public $attributeNames = [
        'id',
        'email-address',
        'first-name', 'last-name', 'formatted-name',
        'phonetic-first-name', 'phonetic-last-name', 'formatted-phonetic-name',
        'public-profile-url',
        'picture-url',
    ];

    /**
     * Get user openid and other basic information.
     *
     * @return array
     */
    protected function initUserAttributes()
    {
        return $this->api('people/~:(' . implode(',', $this->attributeNames) . ')', 'GET');
    }
}
