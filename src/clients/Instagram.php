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
 * Instagram OAuth2
 *
 * In order to use Instagram OAuth2 you must register your application at <https://www.instagram.com/developer/>.
 *
 * Note:  Authorization `Callback URL` can contain `localhost` or `127.0.0.1` for testing.
 *
 * Sample `Callback URL`: 
 * `http://localhost/1_oauth/frontend/web/index.php` (OK)
 * 
 * Example application configuration:
 *
 * ```php
 * 'components' => [
 *     'authClientCollection' => [
 *         'class' => 'yii\authclient\Collection',
 *         'clients' => [
 *             'instagram' => [
 *                 'class' => 'yii\authclient\clients\Instagram',
 *                 'clientId' => 'instagram_client_id',
 *                 'clientSecret' => 'instagram_client_secret',
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
 * {"access_token": "4476057631.70a18ce.d8158605d14546d1b0d25c9f682e10cf", "user": {"username": "yongtiger2544", "bio": "", "website": "", "profile_picture": "https://scontent-lga3-1.cdninstagram.com/t51.2885-19/11906329_960233084022564_1448528159_a.jpg", "full_name": "yongtiger", "id": "4476057631"}}
 * ```
 *
 * getUserAttributes():
 *
 * ```php
    Array
    (
        [meta] => Array
            (
                [code] => 200
            )

        [data] => Array
            (
                [username] => yongtiger2544
                [bio] => 
                [website] => 
                [profile_picture] => https://scontent-ort2-1.cdninstagram.com/t51.2885-19/11906329_960233084022564_1448528159_a.jpg
                [full_name] => yongtiger
                [counts] => Array
                    (
                        [media] => 0
                        [followed_by] => 0
                        [follows] => 0
                    )

                [id] => 4476057631
            )

    )
 * ```
 *
 * [REFERENCES]
 *
 * @see https://www.instagram.com/developer/
 */
class Instagram extends OAuth2 implements IAuth
{
    use ClientTrait;

    /**
     * @inheritdoc
     */
    public $authUrl = 'https://api.instagram.com/oauth/authorize';

    /**
     * @inheritdoc
     */
    public $tokenUrl = 'https://api.instagram.com/oauth/access_token';

    /**
     * @inheritdoc
     */
    public $apiBaseUrl = 'https://api.instagram.com/v1';

    /**
     * @inheritdoc
     */
    protected function defaultName()
    {
        return 'instagram';
    }
    
    /**
     * @inheritdoc
     */
    protected function defaultTitle()
    {
        return 'Instagram';
    }

    /**
     * @inheritdoc
     */
    protected function defaultViewOptions() {
        return [
            'popupWidth' => 540,
            'popupHeight' => 360,
        ];
    }

    /**
     * @inheritdoc
     */
    protected function apiInternal($accessToken, $url, $method, array $params, array $headers)
    {
        return $this->sendRequest($method, $url . '?access_token=' . $accessToken->getToken(), $params, $headers);
    }

    /**
     * @inheritdoc
     */
    protected function initUserAttributes()
    {
        return $this->api('users/self', 'GET');
    }

    /**
     * @inheritdoc
     */
    protected function defaultNormalizeUserAttributeMap() {
        return [
            'openid' => ['data', 'id'],

            'fullname' => ['data', 'full_name'],

            'avatarUrl' => ['data', 'profile_picture'],

            'linkUrl' => ['data', 'website'],
        ];
    }
}