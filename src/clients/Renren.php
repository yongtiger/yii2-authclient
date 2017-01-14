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
 * Renren OAuth2
 *
 * In order to use Renren OAuth2 you must register your application at <https://developer.renren.com>.
 *
 * Example application configuration:
 *
 * ```php
 * 'components' => [
 *     'authClientCollection' => [
 *         'class' => 'yii\authclient\Collection',
 *         'clients' => [
 *             'renren' => [
 *                 'class' => 'yii\authclient\clients\Renren',
 *                 'clientId' => 'renren_client_id',
 *                 'clientSecret' => 'renren_client_secret',
 *             ],
 *         ],
 *     ]
 *     // ...
 * ]
 * ```
 *
 * @see https://developer.renren.com/
 */
class Renren extends OAuth2 implements IAuth
{
    /**
     * @inheritdoc
     */
    public $authUrl = 'https://graph.renren.com/oauth/authorize';
    /**
     * @inheritdoc
     */
    public $tokenUrl = 'https://graph.renren.com/oauth/token';
    /**
     * @inheritdoc
     */
    public $apiBaseUrl = 'https://api.renren.com';
    /**
     * Try to use getUserAttributes to get simple user info
     * @see http://wiki.dev.renren.com/wiki/Authentication
     * @inheritdoc
     */
    protected function initUserAttributes()
    {
        return $this->getAccessToken()->getParams()['user'];
    }
    /**
     * Get authed user info
     * @see http://wiki.dev.renren.com/wiki/V2/user/get
     * @return array
     */
    public function getUserInfo()
    {
        $user = $this->getUserAttributes();
        return $this->api("v2/user/get", 'GET', ['userId' => $user['id']]);
    }
    /**
     * @inheritdoc
     */
    protected function defaultName()
    {
        return 'renren';
    }
    /**
     * @inheritdoc
     */
    protected function defaultTitle()
    {
        return '人人登陆';
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
}


// <?php
// namespace xj\oauth;
// use yii\authclient\OAuth2;
// /**
//  * Renren OAuth
//  * @author light <light-li@hotmail.com>
//  * @see http://wiki.dev.renren.com/wiki/Authentication
//  */
// class RenrenAuth extends OAuth2 implements IAuth
// {
//     /**
//      * @inheritdoc
//      */
//     public $authUrl = 'https://graph.renren.com/oauth/authorize';
//     /**
//      * @inheritdoc
//      */
//     public $tokenUrl = 'https://graph.renren.com/oauth/token';
//     /**
//      * @inheritdoc
//      */
//     public $apiBaseUrl = 'https://api.renren.com';
//     /**
//      * Try to use getUserAttributes to get simple user info
//      * @see http://wiki.dev.renren.com/wiki/Authentication
//      *
//      * @inheritdoc
//      */
//     protected function initUserAttributes()
//     {
//         return $this->getAccessToken()->getParams()['user'];
//     }
//     /**
//      * Get authed user info
//      *
//      * @see http://wiki.dev.renren.com/wiki/V2/user/get
//      * @return array
//      */
//     public function getUserInfo()
//     {
//         return $this->api("v2/user/get", 'GET', ['userId' => $this->getOpenid()]);
//     }
//     /**
//      * @return int
//      */
//     public function getOpenid()
//     {
//         $attributes = $this->getUserAttributes();
//         return $attributes['id'];
//     }
//     /**
//      * @inheritdoc
//      */
//     protected function defaultName()
//     {
//         return 'renren';
//     }
//     /**
//      * @inheritdoc
//      */
//     protected function defaultTitle()
//     {
//         return 'Renren';
//     }
// }