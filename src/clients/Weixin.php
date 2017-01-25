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
use yii\base\Event;
use yii\httpclient\Request;

/**
 * Weixin OAuth2
 *
 * In order to use Weixin OAuth2 you must register your application at <https://open.weixin.qq.com/>.
 *
 * Note: Authorization `Callback URL` can contain `localhost` or `127.0.0.1` for testing.
 *
 * Sample `Callback URL`:
 *
 * `http://localhost` (OK)
 * `http://localhost/1_oauth/frontend/web/index.php/site/auth` (WRONG!)
 * `http://localhost/1_oauth/frontend/web/index.php/site/auth?authclient=weixin` (WRONG!)
 * `http://localhost/1_oauth/frontend/web/index.php?r=site/auth` (WRONG!)
 * `http://localhost/1_oauth/frontend/web/index.php` (WRONG!)
 * `http://localhost/1_oauth/frontend/web` (WRONG!)
 * `http://127.0.0.1` (OK)
 * `http://127.0.0.1/1_oauth/frontend/web/index.php/site/auth` (WRONG!)
 * `http://127.0.0.1/1_oauth/frontend/web/index.php/site/auth?authclient=weixin` (WRONG!)
 * `http://127.0.0.1/1_oauth/frontend/web/index.php?r=site/auth` (WRONG!)
 * `http://127.0.0.1/1_oauth/frontend/web/index.php` (WRONG!)
 * `http://127.0.0.1/1_oauth/frontend/web` (WRONG!)
 *
 * Example application configuration:
 *
 * ```php
 * 'components' => [
 *     'authClientCollection' => [
 *         'class' => 'yii\authclient\Collection',
 *         'clients' => [
 *             'weixin' => [
 *                 'class' => 'yii\authclient\clients\Weixin',
 *                 'clientId' => 'weixin_client_id',
 *                 'clientSecret' => 'weixin_client_secret',
*                  ///'scope' => 'snsapi_login',
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
 * https://open.weixin.qq.com/connect/qrconnect?client_id=wx2634dbab565e2f27&response_type=code&redirect_uri=http%3A%2F%2Flocalhost%2F1_oauth%2Ffrontend%2Fweb%2Findex.php%3Fr%3Dsite%252Fauth%26authclient%3Dweixin&xoauth_displayname=My%20Application&scope=snsapi_login&state=b310de5e32721920cfe992b1b416658d88d763cc63f3e9c6 391216513e49c8c3
 * ```
 *
 * AccessToken Request:
 *
 * ```
 * https://api.weixin.qq.com/sns/oauth2/access_token
 * ```
 *
 * AccessToken Response:
 *
 * ```
 * {"access_token":"3vXAk2gh4sxg0oINBgGqphbb7qOYfJiV1tMwDIUWo7b-6v1bNYFTCuYw0ls-2dBfhecj6fRsoGAUJGD9Mp8YDFvC9zYsR8NQtCqANjohAYo","expires_in":7200,"refresh_token":"Cfp8Q-yWY5Vg9IZ84swJq8uAEaaL8qUZ2sAbxmfu1DmtGdda0TCqnlvjw86YXS16yVOmv88pp_l_arPZXTfYl3bB-gwUdYuHA7TNGOyBH5k","openid":"oZVESxPul-3TOXFgCZra0g45Ky2g","scope":"snsapi_login","unionid":"o4sFWs-RwG0zuImUmdaMmErLxKOo"}
 * ```
 *
 * Request of `initUserAttributes()`:
 *
 * ```
 * https://api.weixin.qq.com/sns/userinfo?openid=oZVESxPul-3TOXFgCZra0g45Ky2g&access_token=3vXAk2gh4sxg0oINBgGqphbb7qOYfJiV1tMwDIUWo7b-6v1bNYFTCuYw0ls-2dBfhecj6fRsoGAUJGD9Mp8YDFvC9zYsR8NQtCqANjohAYo
 * ```
 *
 * Response of `initUserAttributes()`:
 *
 * ```
 * {"openid":"oZVESxPul-3TOXFgCZra0g45Ky2g","nickname":"老虎","sex":1,"language":"zh_CN","city":"","province":"Beijing","country":"CN","headimgurl":"http:\/\/wx.qlogo.cn\/mmopen\/ajNVdqHZLLAjDp1pr7oRywzDAAQXbhV2iamDW2rGxFhjswg91Is913d3t7cNU5CH7De9AhPbh1pE98RqOic57Q5Q\/0","privilege":[],"unionid":"o4sFWs-RwG0zuImUmdaMmErLxKOo"}
 * ```
 *
 * ```php
 * Array
 * (
 *     [openid] => oZVESxPul-3TOXFgCZra0g45Ky2g
 *     [nickname] => 老虎
 *     [sex] => 1
 *     [language] => zh_CN
 *     [city] => 
 *     [province] => Beijing
 *     [country] => CN
 *     [headimgurl] => http://wx.qlogo.cn/mmopen/ajNVdqHZLLAjDp1pr7oRywzDAAQXbhV2iamDW2rGxFhjswg91Is913d3t7cNU5CH7De9AhPbh1pE98RqOic57Q5Q/0
 *     [privilege] => Array
 *        (
 *        )
 * 
 *     [unionid] => o4sFWs-RwG0zuImUmdaMmErLxKOo
 *     [provider] => weixin
 *     [fullname] => 老虎
 *     [gender] => 1
 *     [avatar] => http://wx.qlogo.cn/mmopen/ajNVdqHZLLAjDp1pr7oRywzDAAQXbhV2iamDW2rGxFhjswg91Is913d3t7cNU5CH7De9AhPbh1pE98RqOic57Q5Q/0
 * )
 * ```
 *
 * [REFERENCES]
 *
 * @see https://open.weixin.qq.com/
 * @see https://open.weixin.qq.com/cgi-bin/showdocument?action=dir_list&t=resource/res_list&verify=1&id=open1419316505&token=3caf524ceaf969e25b0bf12ff39a5a192676635d
 * @see https://open.weixin.qq.com/cgi-bin/showdocument?action=doc&id=open1419316518&t=0.14920092844688204
 */
class Weixin extends OAuth2 implements IAuth
{
    use ClientTrait;

    /**
     * @inheritdoc
     */
    public $authUrl = 'https://open.weixin.qq.com/connect/qrconnect';

    /**
     * @inheritdoc
     */
    public $tokenUrl = 'https://api.weixin.qq.com/sns/oauth2/access_token';

    /**
     * @inheritdoc
     */
    public $apiBaseUrl = 'https://api.weixin.qq.com';

    /**
     * @inheritdoc
     */
    public $scope = 'snsapi_login';

    /**
     * @inheritdoc
     */
    protected function defaultName()
    {
        return 'weixin';
    }

    /**
     * @inheritdoc
     */
    protected function defaultTitle()
    {
        return 'Weixin';
    }

    /**
     * @inheritdoc
     */
    protected function defaultViewOptions()
    {
        return [
            'popupWidth'  => 800,
            'popupHeight' => 500,
        ];
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        ///Add `appid` and `secret` before send request.
        if (isset($_GET['code'])) {
            $clientInstance = &$this;
            Event::on(Request::className(), Request::EVENT_BEFORE_SEND, function ($event) use ($clientInstance){
                $request = $event->sender;
                if ($request->url == $clientInstance->tokenUrl) {
                    $request->addData(['appid' => $this->clientId, 'secret' => $this->clientSecret]);
                }
            });
        }
    }

    /**
     * @inheritdoc
     */
    public function buildAuthUrl(array $params = [])
    {
        $params = array_merge($params, ['appid' => $this->clientId]);   ///add `appid`.
        return parent::buildAuthUrl($params);
    }

    /**
     * @inheritdoc
     */
    protected function defaultNormalizeUserAttributeMap()
    {
        return [
            'provider' => function ($attributes) {
                return $this->defaultName();
            },
            'fullname' => 'nickname',
            'gender' => 'sex',
            'avatar' => 'headimgurl',
        ];
    }

    /**
     * Get user openid and other basic information.
     *
     * @return array
     */
    protected function initUserAttributes()
    {
        return $this->api('sns/userinfo', 'GET');
    }

    /**
     * @inheritdoc
     */
    public function beforeApiRequestSend($event)
    {
        $event->request->addData(['openid' => $this->accessToken->getParam('openid')]); ///add `openid`
        parent::beforeApiRequestSend($event);
    }
}
