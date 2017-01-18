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
use yii\authclient\OAuth2;
use yii\base\Event;
use yii\httpclient\Request;

/**
 * Reddit OAuth2
 *
 * In order to use Reddit OAuth you must register your application at <https://www.reddit.com/prefs/apps/>.
 *
 * Note:  Authorization `Callback URL` can contain `localhost` or `127.0.0.1` for testing, also can contain `?`, but NO `&`.
 *
 * Sample `Callback URL`:
 * `http://localhost/1_oauth/frontend/web/index.php/site/auth?authclient=reddit` (OK)
 * `http://localhost/1_oauth/frontend/web/index.php/site/auth` (WRONG!)
 * `http://localhost/1_oauth/frontend/web/index.php?site=auth` (WRONG!)
 * `http://localhost/1_oauth/frontend/web/index.php` (WRONG!)
 * `http://localhost/1_oauth/frontend/web` (WRONG!)
 * `http://localhost` (WRONG!)
 *
 * Example application configuration:
 *
 * ```php
 * 'components' => [
 *     'authClientCollection' => [
 *         'class' => 'yii\authclient\Collection',
 *         'clients' => [
 *             'reddit' => [
 *                 'class' => 'yii\authclient\clients\Reddit',
 *                 'clientId' => 'reddit_client_id',
 *                 'clientSecret' => 'reddit_client_secret',
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
 * {"is_employee": false, "name": "yongtiger", "created": 1484725989.0,  "hide_from_robots": false, "is_suspended": false, "created_utc": 1484697189.0, "link_karma": 1,  "in_beta": false, "comment_karma": 0, "over_18": false, "is_gold": false, "is_mod": false, "id": "14jblb", "gold_expiration": null, "inbox_count": 0, "has_verified_email": true, "gold_creddits": 0, "suspension_expiration_utc": null}
 * ```
 *
 * getUserAttributes():
 *
 * ```php
    Array
    (
        [is_employee] =>
        [name] => yongtiger
        [created] => 1484725989
        [hide_from_robots] =>
        [is_suspended] =>
        [created_utc] => 1484697189
        [link_karma] => 1
        [in_beta] =>
        [comment_karma] => 0
        [over_18] =>
        [is_gold] =>
        [is_mod] =>
        [id] => 14jblb
        [gold_expiration] =>
        [inbox_count] => 0
        [has_verified_email] => 1
        [gold_creddits] => 0
        [suspension_expiration_utc] =>
        [openid] => 14jblb
        [fullname] => yongtiger
    )
 * ```
 *
 * [REFERENCES]
 *
 * Authorization:
 *
 * ```
 * https://www.reddit.com/api/v1/authorize?client_id=CLIENT_ID&response_type=TYPE&state=RANDOM_STRING&redirect_uri=URI&duration=DURATION&scope=SCOPE_STRING
 * 
 * Retrieving the access token:
 *
 * ```
 * https://www.reddit.com/api/v1/access_token
 * ```
 *
 * Include the following information in your POST data (NOT as part of the URL)
 *
 * ```
 * grant_type=authorization_code&code=CODE&redirect_uri=URI
 * ```
 *
 * CURL:
 *
 * ```
 * curl https://www.reddit.com/api/v1/access_token -D- --user CLIENT_ID --header Authorization: Basic BASE64_ENCODE_OF_{CLIENT_ID:CLIENT_SECRET} -F grant_type=authorization_code -F code=CODE -F redirect_uri=URI
 *
 * curl https://oauth.reddit.com/api/v1/me -D- --user CLIENT_ID --header Authorization: Basic ACCESS_TOKEN
 * ```
 *
 * @see https://www.reddit.com/dev/api/oauth
 * @see https://github.com/reddit/reddit/wiki/OAuth2
 * @see https://github.com/reddit/reddit/wiki/OAuth2#authorization
 * @see https://github.com/reddit/reddit/wiki/OAuth2#retrieving-the-access-token
 */
class Reddit extends OAuth2 implements IAuth
{
    use ClientTrait;

    /**
     * @inheritdoc
     */
    public $authUrl = 'https://www.reddit.com/api/v1/authorize';

    /**
     * @inheritdoc
     */
    public $tokenUrl = 'https://www.reddit.com/api/v1/access_token';

    /**
     * @inheritdoc
     */
    public $apiBaseUrl = 'https://oauth.reddit.com/api/v1';

    /**
     * @inheritdoc
     */
    public $scope = 'identity';

    /**
     * @var string duration which according to Reddit, it can be either "temporary" or "permanent"
     * @see https://github.com/reddit/reddit/wiki/OAuth2#authorization
     */
    public $duration = 'temporary';

    /**
     * @var string token state, which you can arbitrarily specified
     * According to Reddit, you should use this to verify authorization requests by checking that it matches.
     * @see https://github.com/reddit/reddit/wiki/OAuth2#authorization
     */
    public $state = 'd8158605d14546d1b0d25c9f682e10cf';

    /**
     * @inheritdoc
     */
    protected function defaultName()
    {
        return 'reddit';
    }

    /**
     * @inheritdoc
     */
    protected function defaultTitle()
    {
        return 'Reddit';
    }

    /**
     * @inheritdoc
     */
    protected function defaultViewOptions()
    {
        return [
            'popupWidth' => 840,
            'popupHeight' => 680,
        ];
    }

    /**
     * @inheritdoc
     */
    protected function defaultNormalizeUserAttributeMap() {
        return [
            'openid' => 'id',

            'fullname' => 'name',
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        ///Add HTTP Basic Authorization headers for getting access token before send request. @see https://github.com/reddit/reddit/wiki/OAuth2#retrieving-the-access-token
        if (isset($_GET['code'])) {

            ///According to Reddit, keep state unchanged. @see https://github.com/reddit/reddit/wiki/OAuth2#authorization.
            $this->validateAuthState = false;

            $clientInstance = &$this;
            Event::on(Request::className(), Request::EVENT_BEFORE_SEND, function ($event) use ($clientInstance){
                $request = $event->sender;
                if ($request->url == $clientInstance->tokenUrl) {
                    $request->addHeaders(['Authorization' => 'Basic ' . base64_encode("{$request->data['client_id']}:{$request->data['client_secret']}")]);
                }
            });

        }
    }

    /**
     * @inheritdoc
     */
    protected function initUserAttributes()
    {
        return $this->api('me', 'GET', [], ['Authorization' => 'Bearer ' . $this->getAccessToken()->getToken()]);   ///add Bearer token for api requests
    }

    /**
     * Add state and duration to $defaultParams
     * @inheritdoc
     */
    public function buildAuthUrl(array $params = [])
    {
        $params = [
            'state' => $this->state,    ///add state to $defaultParams
            'duration' => $this->duration,  ///add duration to $defaultParams
        ];
        return parent::buildAuthUrl($params);
    }

}