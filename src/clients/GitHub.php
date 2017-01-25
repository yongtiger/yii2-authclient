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
 * GitHub OAuth2
 *
 * In order to use GitHub OAuth2 you must register your application at <https://github.com/settings/applications/new>.
 *
 * Note: Authorization `Callback URL` can contain `localhost` or `127.0.0.1` for testing.
 *
 * Sample `Callback URL`:
 *
 * `http://localhost/1_oauth/frontend/web/index.php?r=site/auth` (OK)
 * `http://localhost/1_oauth/frontend/web/index.php?r=site%2Fauth` (OK)
 * `http://localhost/1_oauth/frontend/web/index.php?r=site/auth&authclient=github` (OK)
 * `http://localhost/1_oauth/frontend/web/index.php?r=site%2Fauth&authclient=github` (OK)
 * `http://localhost/1_oauth/frontend/web/index.php?r=site%2Fauth%26authclient=github` (WRONG!)
 * `http://localhost/1_oauth/frontend/web/?r=site/auth` (OK)
 * `http://localhost/1_oauth/frontend/web/?r=site%2Fauth` (OK)
 * `http://localhost/1_oauth/frontend/web/?r=site/auth&authclient=github` (OK)
 * `http://localhost/1_oauth/frontend/web/?r=site%2Fauth&authclient=github` (OK)
 * `http://localhost/1_oauth/frontend/web/?r=site%2Fauth%26authclient=github` (WRONG!)
 * `http://127.0.0.1/1_oauth/frontend/web/index.php?r=site/auth` (OK)
 * `http://127.0.0.1/1_oauth/frontend/web/index.php?r=site%2Fauth` (OK)
 * `http://127.0.0.1/1_oauth/frontend/web/index.php?r=site/auth&authclient=github` (OK)
 * `http://127.0.0.1/1_oauth/frontend/web/index.php?r=site%2Fauth&authclient=github` (OK)
 * `http://127.0.0.1/1_oauth/frontend/web/index.php?r=site%2Fauth%26authclient=github` (WRONG!)
 * `http://127.0.0.1/1_oauth/frontend/web/?r=site/auth` (OK)
 * `http://127.0.0.1/1_oauth/frontend/web/?r=site%2Fauth` (OK)
 * `http://127.0.0.1/1_oauth/frontend/web/?r=site/auth&authclient=github` (OK)
 * `http://127.0.0.1/1_oauth/frontend/web/?r=site%2Fauth&authclient=github` (OK)
 * `http://127.0.0.1/1_oauth/frontend/web/?r=site%2Fauth%26authclient=github` (WRONG!)
 *
 * Example application configuration:
 *
 * ```php
 * 'components' => [
 *     'authClientCollection' => [
 *         'class' => 'yii\authclient\Collection',
 *         'clients' => [
 *             'github' => [
 *                 'class' => 'yongtiger\authclient\clients\GitHub',
 *                 'clientId' => 'github_client_id',
 *                 'clientSecret' => 'github_client_secret',
 *                 ///'scope' => 'user:email user',
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
 * https://github.com/login?client_id=d9bf109efa527c68d1a7&return_to=%2Flogin%2Foauth%2Fauthorize%3Fclient_id%3Dd9bf109efa527c68d1a7%26redirect_uri%3Dhttp%253A%252F%252Flocalhost%252F1_oauth%252Ffrontend%252Fweb%252Findex.php%253Fr%253Dsite%25252Fauth%2526authclient%253Dgithub%26response_type%3Dcode%26scope%3Duser%26state%3Dbaeacf933b962687951a1b0c29beeab701f876e6e1c3a6f12d96e7e413f77329%26xoauth_displayname%3DMy%2BApplication
 * ```
 *
 * AccessToken Request:
 *
 * ```
 * https://github.com/login/oauth/access_token
 * ```
 *
 * AccessToken Response:
 *
 * ```
 * access_token=75bc70ac70976c5144b483f0bc1c2f97ec167fda&scope=user&token_type=bearer
 * ```
 *
 * Request of `initUserAttributes()`:
 *
 * ```
 * https://api.github.com/user?access_token=75bc70ac70976c5144b483f0bc1c2f97ec167fda
 * ```
 *
 * Response of `initUserAttributes()`:
 *
 * ```
 * {"login":"yongtiger","id":19513015,"avatar_url":"https://avatars.githubusercontent.com/u/19513015?v=3","gravatar_id":"","url":"https://api.github.com/users/yongtiger","html_url":"https://github.com/yongtiger","followers_url":"https://api.github.com/users/yongtiger/followers","following_url":"https://api.github.com/users/yongtiger/following{/other_user}","gists_url":"https://api.github.com/users/yongtiger/gists{/gist_id}","starred_url":"https://api.github.com/users/yongtiger/starred{/owner}{/repo}","subscriptions_url":"https://api.github.com/users/yongtiger/subscriptions","organizations_url":"https://api.github.com/users/yongtiger/orgs","repos_url":"https://api.github.com/users/yongtiger/repos","events_url":"https://api.github.com/users/yongtiger/events{/privacy}","received_events_url":"https://api.github.com/users/yongtiger/received_events","type":"User","site_admin":false,"name":null,"company":null,"blog":null,"location":null,"email":null,"hireable":null,"bio":null,"public_repos":3,"public_gists":0,"followers":0,"following":0,"created_at":"2016-05-22T05:26:43Z","updated_at":"2017-01-19T04:26:59Z","private_gists":0,"total_private_repos":0,"owned_private_repos":0,"disk_usage":326,"collaborators":0,"plan":{"name":"free","space":976562499,"collaborators":0,"private_repos":0}}
 * ```
 *
 * ```php
 * Array
 * (
 *     [login] => yongtiger
 *     [id] => 19513015
 *     [avatar_url] => https://avatars.githubusercontent.com/u/19513015?v=3
 *     [gravatar_id] => 
 *     [url] => https://api.github.com/users/yongtiger
 *     [html_url] => https://github.com/yongtiger
 *     [followers_url] => https://api.github.com/users/yongtiger/followers
 *     [following_url] => https://api.github.com/users/yongtiger/following{/other_user}
 *     [gists_url] => https://api.github.com/users/yongtiger/gists{/gist_id}
 *     [starred_url] => https://api.github.com/users/yongtiger/starred{/owner}{/repo}
 *     [subscriptions_url] => https://api.github.com/users/yongtiger/subscriptions
 *     [organizations_url] => https://api.github.com/users/yongtiger/orgs
 *     [repos_url] => https://api.github.com/users/yongtiger/repos
 *     [events_url] => https://api.github.com/users/yongtiger/events{/privacy}
 *     [received_events_url] => https://api.github.com/users/yongtiger/received_events
 *     [type] => User
 *     [site_admin] => 
 *     [name] => 
 *     [company] => 
 *     [blog] => 
 *     [location] => 
 *     [email] => 
 *     [hireable] => 
 *     [bio] => 
 *     [public_repos] => 3
 *     [public_gists] => 0
 *     [followers] => 0
 *     [following] => 0
 *     [created_at] => 2016-05-22T05:26:43Z
 *     [updated_at] => 2017-01-19T04:26:59Z
 *     [private_gists] => 0
 *     [total_private_repos] => 0
 *     [owned_private_repos] => 0
 *     [disk_usage] => 326
 *     [collaborators] => 0
 *     [provider] => github
 *     [openid] => 19513015
 *     [fullname] => yongtiger
 *     [avatar] => https://avatars.githubusercontent.com/u/19513015?v=3
 *     [link] => https://api.github.com/users/yongtiger
 * )
 * ```
 *
 * Request of `initUserInfoAttributes()`:
 *
 * ```
 * https://api.github.com/user/emails?access_token=75bc70ac70976c5144b483f0bc1c2f97ec167fda
 * ```
 *
 * Response of `initUserInfoAttributes()`:
 *
 * ```
 * [{"email":"3196127698@qq.com","primary":true,"verified":true},{"email":"tigeryang.brainbook@outlook.com","primary":false,"verified":true}]
 * ```
 *
 * ```php
 * Array
 *     (
 *         [0] => Array
 *             (
 *                 [email] => 3196127698@qq.com
 *                 [primary] => 1
 *                 [verified] => 1
 *             )
 * 
 *         [1] => Array
 *             (
 *                 [email] => tigeryang.brainbook@outlook.com
 *                 [primary] => 
 *                 [verified] => 1
 *             )
 * 
 *     )
 * ```
 *
 * [REFERENCES]
 *
 * @see http://developer.github.com/v3/oauth/
 * @see http://www.yiiframework.com/doc-2.0/yii-authclient-clients-github.html
 */
class GitHub extends \yii\authclient\clients\GitHub implements IAuth
{
    use ClientTrait;

    /**
     * @inheritdoc
     */
    protected function defaultViewOptions() {
        return [
            'popupWidth' => 860,
            'popupHeight' => 480,
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
            'openid' => 'id',
            'fullname' => 'login',
            'avatar' => 'avatar_url',
            'link' => 'url',
        ];
    }

    /**
     * Normalize user info attribute map.
     *
     * @return array
     */
    protected function userInfoNormalizeUserAttributeMap() {
        return [
            'email' => function ($attributes) {
                $emails = $attributes['emails'];
                if (!empty($emails)) {
                    foreach ($emails as $email) {
                        if ($email['primary'] == 1 && $email['verified'] == 1) {
                            return $email['email'];
                        }
                    }
                }
                return null;
            },
        ];
    }

    /**
     * Get user openid and other basic information.
     *
     * @return array
     */
    protected function initUserAttributes()
    {
        return $this->api('user', 'GET');
    }

    /**
     * Get extra user info.
     *
     * @return array
     */
    protected function initUserInfoAttributes()
    {
        return ['emails' => $this->api('user/emails', 'GET')];
    }
}
