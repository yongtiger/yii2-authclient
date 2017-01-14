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
 * Microsoft Live OAuth2
 *
 * In order to use Microsoft Live OAuth2 you must register your application at <https://account.live.com/developers/applications>
 *
 * Example application configuration:
 *
 * ```php
 * 'components' => [
 *     'authClientCollection' => [
 *         'class' => 'yii\authclient\Collection',
 *         'clients' => [
 *             'live' => [
 *                 'class' => 'yii\authclient\clients\Live',
 *                 'clientId' => 'live_client_id',
 *                 'clientSecret' => 'live_client_secret',
 *             ],
 *         ],
 *     ]
 *     ...
 * ]
 * ```
 *
 * @see https://account.live.com/developers/applications
 * @see http://msdn.microsoft.com/en-us/library/live/hh243647.aspx
 */
class Live extends \yii\authclient\clients\Live implements IAuth
{
    /**
     * @inheritdoc
     */
    public $authUrl = 'https://login.live.com/oauth20_authorize.srf';
    /**
     * @inheritdoc
     */
    public $tokenUrl = 'https://login.live.com/oauth20_token.srf';
    /**
     * @inheritdoc
     */
    public $apiBaseUrl = 'https://apis.live.net/v5.0';


    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if ($this->scope === null) {
            $this->scope = implode(',', [
                'wl.basic',
                'wl.emails',
            ]);
        }
    }

    /**
     * @inheritdoc
     */
    protected function initUserAttributes()
    {
        return $this->api('me', 'GET');
    }

    /**
     * @inheritdoc
     */
    protected function defaultName()
    {
        return 'live';
    }

    /**
     * @inheritdoc
     */
    protected function defaultTitle()
    {
        return 'Live';
    }
}
