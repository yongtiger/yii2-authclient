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
 * In order to use Renren OAuth2 you must register your application at <https://dev.renren.com>.
 *
 * Note: Has been invalid!!!
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
 * [REFERENCES]
 *
 * @see https://dev.renren.com/
 * @see http://wiki.dev.renren.com/wiki/V2/user/get
 * @see http://wiki.dev.renren.com/wiki/Authentication
 */
class Renren extends OAuth2 implements IAuth
{
    use ClientTrait;

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
        return 'Renren';
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
    protected function defaultNormalizeUserAttributeMap() {
        return [
            'provider' => function ($attributes) {
                return $this->defaultName();
            },
            'openid' => 'id',
        ];
    }

    /**
     * Get user openid and other basic information.
     *
     * @return array
     */
    protected function initUserAttributes()
    {
        return $this->getAccessToken()->getParams()['user'];
    }

    /**
     * Get extra user info.
     *
     * @return array
     */
    protected function initUserInfoAttributes()
    {
        return $this->api("v2/user/get", 'GET', ['userId' => $this->getUserAttributes()['id']]);
    }
}
