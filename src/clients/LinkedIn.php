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
 * Note:  Authorization `Callback URL` can contain `localhost` or `127.0.0.1` for testing.
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
 * [EXAMPLE JSON RESPONSE BODY FOR GET]
 * 
 * `$responseContent` at `/vendor/yiisoft/yii2-httpclient/StreamTransport.php`:
 *
 * ```
 * <?xml version="1.0" encoding="UTF-8" standalone="yes"?>  <person> <id>09EXzPLzAo</id> <email-address>tigeryang.brainbook@outlook.com</email- address> <first-name>光</first-name> <last-name>杨</last-name> <formatted-name>杨光 (tiger  yong)</formatted-name> <public-profile-url>https://www.linkedin.com/in/%E5%85%89-%E6%9D %A8-a91147133</public-profile-url> </person>
 * ```
 *
 * getUserAttributes():
 *
 * ```php
    Array
    (
        [id] => 09EXzPLzAo
        [email-address] => tigeryang.brainbook@outlook.com
        [first-name] => 光
        [last-name] => 杨
        [public-profile-url] => https://www.linkedin.com/in/%E5%85%89-%E6%9D%A8-a91147133
        [formatted-name] => 杨光 (tiger yong)
        [openid] => 09EXzPLzAo
        [email] => tigeryang.brainbook@outlook.com
        [fullname] => 杨光 (tiger yong)
        [firstname] => 光
        [lastname] => 杨
        [linkUrl] => https://www.linkedin.com/in/%E5%85%89-%E6%9D%A8-a91147133
    )
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
     * @inheritdoc
     */
    protected function initUserAttributes()
    {
        return $this->api('people/~:(' . implode(',', $this->attributeNames) . ')', 'GET');
    }
}
