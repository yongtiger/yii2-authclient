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
use yii\web\HttpException;

/**
 * Amazon OAuth2
 *
 * In order to use Amazon OAuth2 you must register your application at <http://login.amazon.com/manageApps>.
 *
 * Note: The redirect URI for web apps and services must begin with the scheme `https`. That means you cannot use `localhost` or `127.0.0.1` for testing!
 *
 * Sample `Callback URL`: 
 * `https://yourdomain/1_oauth/frontend/web/index.php/site/auth?authclient=amazon` (OK)
 * `https://yourdomain/1_oauth/frontend/web/index.php?r=site/auth` (WRONG!)
 * `https://yourdomain/1_oauth/frontend/web/index.php?r=site/auth&authclient=amazon` (WRONG!)
 * 
 * Example application configuration:
 *
 * ```php
 * 'components' => [
 *     'authClientCollection' => [
 *         'class' => 'yii\authclient\Collection',
 *         'clients' => [
 *             'amazon' => [
 *                 'class' => 'yii\authclient\clients\Amazon',
 *                 'clientId' => 'amazon_client_id',
 *                 'clientSecret' => 'amazon_client_secret',
 *             ],
 *         ],
 *     ]
 *     // ...
 * ]
 * ```
 *
 *
 * [EXAMPLE JSON RESPONSE BODY FOR GET]
 * 
 * `$responseContent` at `/vendor/yiisoft/yii2-httpclient/StreamTransport.php`:
 *
 * ```
 * {"name":"Tiger Yong","email":"tigeryang.brainbook
 \u0040outlook.com","first_name":"Tiger","last_name":"Yong","picture":{"data":
 {"is_silhouette":true,"url":"https:\/\/scontent.xx.fbcdn.net\/v\/t1.0-1\/c15.0.50.50\/p50x50\/
 10354686_10150004552801856_220367501106153455_n.jpg?
 oh=978df650af5b925f321fe4050af2869f&oe=5911542F"}},"gender":"male","age_range":{"min":
 21},"link":"https:\/\/www.facebook.com\/app_scoped_user_id\/
 123618604810465\/","locale":"en_US","timezone":
 8,"updated_time":"2017-01-13T21:40:58+0000","verified":true,"id":"123618604810465"}
 * ```
 *
 * getUserAttributes():
 *
 * ```php
    Array
    (
        [aud] => amzn1.application-oa2-client.c3b583b90a274981a36d53f47234f111
        [user_id] => amzn1.account.AHQG2JPGF47GOIO4WYGDWAHKTIVA
        [iss] => https://www.amazon.com
        [exp] => 3599
        [app_id] => amzn1.application.d65c23fdd3c64214bafc7a604820ffe9
        [iat] => 1484707047
        [openid] => amzn1.account.AHQG2JPGF47GOIO4WYGDWAHKTIVA
    )
 * ```
 *
 * getUserInfo($attribute):
 *
 * ```php
    Array
    (
        [aud] => amzn1.application-oa2-client.c3b583b90a274981a36d53f47234f111
        [user_id] => amzn1.account.AHQG2JPGF47GOIO4WYGDWAHKTIVA
        [iss] => https://www.amazon.com
        [exp] => 3599
        [app_id] => amzn1.application.d65c23fdd3c64214bafc7a604820ffe9
        [iat] => 1484707359
        [openid] => amzn1.account.AHQG2JPGF47GOIO4WYGDWAHKTIVA
        [name] => yongtiger
        [email] => tigeryang.brainbook@outlook.com
        [fullname] => yongtiger
    )
 * ```
 *
 * [REFERENCES]
 *
 * @see http://login.amazon.com/website
 * @see https://images-na.ssl-images-amazon.com/images/G/01/lwa/dev/docs/website-developer-guide._TTH_.pdf
 */
class Amazon extends OAuth2 implements IAuth
{
    use ClientTrait;

    public $authUrl = 'https://www.amazon.com/ap/oa';
    public $tokenUrl = 'https://api.amazon.com/auth/o2/token';
    public $apiBaseUrl = 'https://api.amazon.com';
    public $scope = 'profile';
    /**
     * Composes user authorization URL.
     * @param array $params additional auth GET params.
     * @return string authorization URL.
     */
    public function buildAuthUrl(array $params = [])
    {
        $defaultParams = [
            'client_id' => $this->clientId,
            'scope' => $this->scope,
            'response_type' => 'code',
            'redirect_uri' => $this->getReturnUrl(),
        ];
        if ($this->validateAuthState) {
            $authState = $this->generateAuthState();
            $this->setState('authState', $authState);
            $defaultParams['state'] = $authState;
        }
        return $this->composeUrl($this->authUrl, array_merge($defaultParams, $params));
    }
    /**
     * Fetches access token from authorization code.
     * @param string $authCode authorization code, usually comes at $_GET['code'].
     * @param array $params additional request params.
     * @return OAuthToken access token.
     * @throws HttpException on invalid auth state in case [[enableStateValidation]] is enabled.
     */
    public function fetchAccessToken($authCode, array $params = [])
    {
        if ($this->validateAuthState) {
            $authState = $this->getState('authState');
            if (!isset($_REQUEST['state']) || empty($authState) || strcmp($_REQUEST['state'], $authState) !== 0) {
                throw new HttpException(400, 'Invalid auth state parameter.');
            } else {
                $this->removeState('authState');
            }
        }
        $defaultParams = [
            'grant_type' => 'authorization_code',
            'code' => $authCode,
            'redirect_uri' => $this->getReturnUrl(),
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
        ];
        $request = $this->createRequest()
            ->setMethod('POST')
            ->addHeaders(['Content-Type' => 'application/x-www--urlencoded;charset=UTF-8'])
            ->setUrl($this->tokenUrl)
            ->setData(array_merge($defaultParams, $params));
        $response = $this->sendRequest($request);
        $token = $this->createToken(['params' => $response]);
        $this->setAccessToken($token);
        return $token;
    }
    /**
     * Clean ReturnUrl scope param
     * @return string
     */
    public function getReturnUrl()
    {
        $returnUrl = parent::getReturnUrl();
        $scope = "scope=" . urlencode($this->scope);
        $returnUrl = str_replace(["&{$scope}", "{$scope}&", "?{$scope}"], '', $returnUrl);
        return $returnUrl;
    }
    /**
     *
     * @return array
     * @see http://open.weibo.com/wiki/Oauth2/get_token_info
     * @see http://open.weibo.com/wiki/2/users/show
     */
    protected function initUserAttributes()
    {
        return $this->api('/auth/O2/tokeninfo');
    }


    protected function defaultName()
    {
        return 'amazon';
    }
    protected function defaultTitle()
    {
        return 'Amazon';
    }


    /**
     * @inheritdoc
     */
    protected function defaultViewOptions() {
        return [
            'popupWidth' => 800,
            'popupHeight' => 600,
        ];
    }

    /**
     * @inheritdoc
     */
    protected function defaultNormalizeUserAttributeMap() {
        return [
            'openid' => 'user_id',
        ];
    }

    /**
     * Proflie Normalize User Attribute Map
     *
     * @return array
     */
    protected function profileNormalizeUserAttributeMap() {
        return [
            'fullname' => 'name',
        ];
    }

    /**
     * @inheritdoc
     */
    public function getUserInfo($attribute)
    {
        if (isset($this->getUserAttributes()[$attribute])) {
            return $this->getUserAttributes()[$attribute];
        }

        ///Get extra user info: profile
        $this->setNormalizeUserAttributeMap($this->profileNormalizeUserAttributeMap());
        $profile = $this->api('/user/profile');
        $attributes = $this->normalizeUserAttributes($profile);
        $this->setUserAttributes(array_merge($this->getUserAttributes(), $attributes));

        return isset($this->getUserAttributes()[$attribute]) ? $this->getUserAttributes()[$attribute] : null;
    }
}