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
 * In order to use Instagram OAuth2 you must register your application at <https://www.instagram.com/>.
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
 * @see https://www.instagram.com/
 * @see https://www.douban.com
 */
class Instagram extends OAuth2 implements IAuth
{
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
    protected function initUserAttributes()
    {
        $response = $this->api('users/self', 'GET');
        return $response['data'];
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
}