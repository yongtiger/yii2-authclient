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
 * In order to use GitHub OAuth2 you must register your application at <https://github.com/settings/applications/new> in your Github account.
 *
 * Note:  Authorization `Callback URL` can contain `localhost` or `127.0.0.1` for testing.
 *
 * Sample `Callback URL`: 
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
 * {  "login": "magicdict",  "id": 897796,  "avatar_url": "https://avatars.githubusercontent.com/u/897796?v=3",  "gravatar_id": "",  "url": "https://api.github.com/users/magicdict",  "html_url": "https://github.com/magicdict",  "followers_url": "https://api.github.com/users/magicdict/followers",  "following_url": "https://api.github.com/users/magicdict/following{/other_user}",  "gists_url": "https://api.github.com/users/magicdict/gists{/gist_id}",  "starred_url": "https://api.github.com/users/magicdict/starred{/owner}{/repo}",  "subscriptions_url": "https://api.github.com/users/magicdict/subscriptions",  "organizations_url": "https://api.github.com/users/magicdict/orgs",  "repos_url": "https://api.github.com/users/magicdict/repos",  "events_url": "https://api.github.com/users/magicdict/events{/privacy}",  "received_events_url": "https://api.github.com/users/magicdict/received_events",  "type": "User",  "site_admin": false,  "name": "MagicHu",  "company": "Shanghai Chuwa software co.ltd",  "blog": "http://www.mywechatapp.com",  "location": "Shanghai,China",  "email": "mynightelfplayer@hotmail.com",  "hireable": true,  "bio": null,  "public_repos": 7,  "public_gists": 0,  "followers": 50,  "following": 2,  "created_at": "2011-07-06T09:26:40Z",  "updated_at": "2016-02-06T09:09:34Z"}
 * 
 * getUserAttributes():
 *
 * ```php
    Array
    (
        [login] => yongtiger
        [id] => 19513015
        [avatar_url] => https://avatars.githubusercontent.com/u/19513015?v=3
        [gravatar_id] => 
        [url] => https://api.github.com/users/yongtiger
        [html_url] => https://github.com/yongtiger
        [followers_url] => https://api.github.com/users/yongtiger/followers
        [following_url] => https://api.github.com/users/yongtiger/following{/other_user}
        [gists_url] => https://api.github.com/users/yongtiger/gists{/gist_id}
        [starred_url] => https://api.github.com/users/yongtiger/starred{/owner}{/repo}
        [subscriptions_url] => https://api.github.com/users/yongtiger/subscriptions
        [organizations_url] => https://api.github.com/users/yongtiger/orgs
        [repos_url] => https://api.github.com/users/yongtiger/repos
        [events_url] => https://api.github.com/users/yongtiger/events{/privacy}
        [received_events_url] => https://api.github.com/users/yongtiger/received_events
        [type] => User
        [site_admin] => 
        [name] => 
        [company] => 
        [blog] => 
        [location] => 
        [email] => 3196127698@qq.com
        [hireable] => 
        [bio] => 
        [public_repos] => 3
        [public_gists] => 0
        [followers] => 0
        [following] => 0
        [created_at] => 2016-05-22T05:26:43Z
        [updated_at] => 2016-12-06T04:10:27Z
        [private_gists] => 0
        [total_private_repos] => 0
        [owned_private_repos] => 0
        [disk_usage] => 120
        [collaborators] => 0
        [plan] => Array
            (
                [name] => free
                [space] => 976562499
                [collaborators] => 0
                [private_repos] => 0
            )

    )
 * ```
 *
 * [REFERENCES]
 *
 * @see http://developer.github.com/v3/oauth/
 * @see http://www.yiiframework.com/doc-2.0/yii-authclient-clients-github.html
 */
class GitHub extends \yii\authclient\clients\GitHub implements IAuth
{
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
    public function getEmail()
    {
        return $this->getUserAttributes()['email'] ? : null;
    }

    /**
     * @inheritdoc
     */
    public function getFullName()
    {
        return $this->getUserAttributes()['login'] ? : null;
    }

    /**
     * @inheritdoc
     */
    public function getAvatarUrl()
    {
        return $this->getUserAttributes()['avatar_url'] ? : null;
    }
}
