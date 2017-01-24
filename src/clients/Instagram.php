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
 * Note: Authorization `Callback URL` can contain `localhost` or `127.0.0.1` for testing.
 *
 * Sample `Callback URL`:
 *
 * `http://localhost/1_oauth/frontend/web/index.php` (OK)
 * `http://127.0.0.1/1_oauth/frontend/web/index.php` (OK)
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
 * https://www.instagram.com/accounts/login/?force_classic_login=&next=/oauth/authorize%3Fclient_id%3D70a18ce48a8c4ffb92b0d5b288bed466%26response_type%3Dcode%26redirect_uri%3Dhttp%3A//localhost/1_oauth/frontend/web/index.php/site/auth%3Fauthclient%3Dinstagram%26xoauth_displayname%3DMy%2BApplication%26state%3D9675cbc65dcf33e4227d97a61986de7fb8cfd428dad6eabce3bcd468f5f318b5
 * ```
 *
 * AccessToken Request:
 *
 * ```
 * https://api.instagram.com/oauth/access_token
 * ```
 *
 * AccessToken Response:
 *
 * ```
 * {"access_token": "4476057631.70a18ce.d8158605d14546d1b0d25c9f682e10cf", "user": {"username": "yongtiger2544", "bio": "", "website": "", "profile_picture": "https://instagram.flim2-1.fna.fbcdn.net/t51.2885-19/11906329_960233084022564_1448528159_a.jpg", "full_name": "yongtiger", "id": "4476057631"}}
 * ```
 *
 * Request of `initUserAttributes()`:
 *
 * ```
 * https://api.instagram.com/v1/users/self?access_token=4476057631.70a18ce.d8158605d14546d1b0d25c9f682e10cf
 * ```
 *
 * Response of `initUserAttributes()`:
 *
 * ```
 * {"meta": {"code": 200}, "data": {"username": "yongtiger2544", "bio": "", "website": "", "profile_picture": "https://scontent-lga3-1.cdninstagram.com/t51.2885-19/11906329_960233084022564_1448528159_a.jpg", "full_name": "yongtiger", "counts": {"media": 0, "followed_by": 0, "follows": 0}, "id": "4476057631"}}
 * ```
 *
 * ```php
 * Array
 * (
 *     [meta] => Array
 *         (
 *             [code] => 200
 *         )
 * 
 *     [data] => Array
 *         (
 *             [username] => yongtiger2544
 *             [bio] => 
 *             [website] => 
 *             [profile_picture] => https://scontent.cdninstagram.com/t51.2885-19/11906329_960233084022564_1448528159_a.jpg
 *             [full_name] => yongtiger
 *             [counts] => Array
 *                 (
 *                     [media] => 0
 *                     [followed_by] => 0
 *                     [follows] => 0
 *                 )
 * 
 *             [id] => 4476057631
 *         )
 * 
 *     [provider] => instagram
 *     [openid] => 4476057631
 *     [fullname] => yongtiger
 *     [avatarUrl] => https://scontent.cdninstagram.com/t51.2885-19/11906329_960233084022564_1448528159_a.jpg
 *     [linkUrl] => 
 * )
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
    protected function defaultNormalizeUserAttributeMap() {
        return [
            'provider' => $this->defaultName,
            'openid' => ['data', 'id'],
            'fullname' => ['data', 'full_name'],
            'avatarUrl' => ['data', 'profile_picture'],
            'linkUrl' => ['data', 'website'],
        ];
    }

    /**
     * Get user openid and other basic information.
     *
     * @return array
     */
    protected function initUserAttributes()
    {
        return $this->api('users/self', 'GET');
    }

}