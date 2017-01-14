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
 * Douban OAuth2
 *
 * In order to use Douban OAuth2 you must register your application at <https://www.douban.com/>.
 *
 * Example application configuration:
 *
 * ```php
 * 'components' => [
 *     'authClientCollection' => [
 *         'class' => 'yii\authclient\Collection',
 *         'clients' => [
 *             'douban' => [
 *                 'class' => 'yii\authclient\clients\Douban',
 *                 'clientId' => 'douban_client_id',
 *                 'clientSecret' => 'douban_client_secret',
 *             ],
 *         ],
 *     ]
 *     // ...
 * ]
 * ```
 *
 * @see https://www.douban.com
 * @see http://API
 */
class Douban extends OAuth2 implements IAuth
{
    /**
     * @inheritdoc
     */
    public $authUrl = 'https://www.douban.com/service/auth2/auth';
    /**
     * @inheritdoc
     */
    public $tokenUrl = 'https://www.douban.com/service/auth2/token';
    /**
     * @inheritdoc
     */
    public $apiBaseUrl = 'https://api.douban.com';
    /**
     * @inheritdoc
     */
    public $scope = 'douban_basic_common';
    /**
     * @inheritdoc
     */
    protected function initUserAttributes()
    {
        return $this->api('me', 'GET', [
            'fields' => implode(',', $this->attributeNames),
        ]);
    }

    /**
     * Get authed user info
     * @return array
     * @see http://developers.douban.com/wiki/?title=user_v2#User
     */
    public function getUserInfo()
    {
        return $this->api('v2/user/~me', 'GET');
    }
    protected function defaultName()
    {
        return 'douban';
    }
    protected function defaultTitle()
    {
        return '豆瓣登陆';
    }
    protected function defaultViewOptions()
    {
        return [
            'popupWidth'  => 800,
            'popupHeight' => 500,
        ];
    }
    /**
     * @ineritdoc
     */
    public function api($apiSubUrl, $method = 'GET', array $params = [], array $headers = [])
    {
        if (preg_match('/^https?:\\/\\//is', $apiSubUrl)) {
            $url = $apiSubUrl;
        } else {
            $url = $this->apiBaseUrl . '/' . $apiSubUrl;
        }
        $accessToken = $this->getAccessToken();
        if (!is_object($accessToken) || !$accessToken->getIsValid()) {
            throw new Exception('Invalid access token.');
        }
        $headers[] = 'Authorization: Bearer ' . $accessToken->getToken();
        return $this->apiInternal($accessToken, $url, $method, $params, $headers);
    }
}


// <?php
// namespace xj\oauth;
// use yii\authclient\OAuth2;
// use yii\base\Exception;
// /**
//  * Douban OAuth
//  * @author light <light-li@hotmail.com>
//  */
// class DoubanAuth extends OAuth2 implements IAuth
// {
//     /**
//      * @inheritdoc
//      */
//     public $authUrl = 'https://www.douban.com/service/auth2/auth';
//     /**
//      * @inheritdoc
//      */
//     public $tokenUrl = 'https://www.douban.com/service/auth2/token';
//     /**
//      * @inheritdoc
//      */
//     public $apiBaseUrl = 'https://api.douban.com';
//     /**
//      * @inheritdoc
//      */
//     public $scope = 'douban_basic_common';
//     protected function initUserAttributes()
//     {
//         return $this->api('v2/user/~me', 'GET');
//     }
//     /**
//      * @return array
//      * @see http://developers.douban.com/wiki/?title=user_v2#User
//      */
//     public function getUserInfo()
//     {
//         return $this->getUserAttributes();
//     }
//     /**
//      * @return string
//      */
//     public function getOpenid()
//     {
//         $attributes = $this->getUserAttributes();
//         return $attributes['id'];
//     }
//     protected function defaultName()
//     {
//         return 'douban';
//     }
//     protected function defaultTitle()
//     {
//         return 'Douban';
//     }
//     /**
//      *
//      * @ineritdoc
//      */
//     public function api($apiSubUrl, $method = 'GET', array $params = [], array $headers = [])
//     {
//         if (preg_match('/^https?:\\/\\//is', $apiSubUrl)) {
//             $url = $apiSubUrl;
//         } else {
//             $url = $this->apiBaseUrl . '/' . $apiSubUrl;
//         }
//         $accessToken = $this->getAccessToken();
//         if (!is_object($accessToken) || !$accessToken->getIsValid()) {
//             throw new Exception('Invalid access token.');
//         }
//         $headers[] = 'Authorization: Bearer ' . $accessToken->getToken();
//         return $this->apiInternal($accessToken, $url, $method, $params, $headers);
//     }
// }

// <?php
// /**
//  * @link http://www.tintsoft.com/
//  * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
//  * @license http://www.tintsoft.com/license/
//  */
// namespace xutl\authclient;
// use Yii;
// use yii\authclient\OAuth2;
// class Douban extends OAuth2
// {
//     /**
//      * @inheritdoc
//      */
//     public $authUrl = 'https://www.douban.com/service/auth2/auth';
//     /**
//      * @inheritdoc
//      */
//     public $tokenUrl = 'https://www.douban.com/service/auth2/token';
//     /**
//      * @inheritdoc
//      */
//     public $apiBaseUrl = 'https://api.douban.com/';
//     /**
//      * @inheritdoc
//      */
//     public $scope = 'douban_basic_common';
//     /**
//      * @inheritdoc
//      */
//     protected function initUserAttributes() {
//         return $this->api('v2/user/~me', 'GET');
//     }
//     /**
//      * @return array
//      * @see http://developers.douban.com/wiki/?title=user_v2#User
//      */
//     public function getUserInfo()
//     {
//         return $this->getUserAttributes();
//     }
//     /**
//      * @inheritdoc
//      */
//     protected function defaultName() {
//         return 'douban';
//     }
//     /**
//      * @inheritdoc
//      */
//     protected function defaultTitle() {
//         return Yii::t('app','Douban');
//     }
//     protected function defaultViewOptions() {
//         return [ 'popupWidth'=> 1000, 'popupHeight'=> 500 ];
//     }
// }

