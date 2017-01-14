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
 * Yandex OAuth2
 *
 * In order to use Yandex OAuth2 you must register your application at <https://oauth.yandex.ru/client/new>.
 *
 * Example application configuration:
 *
 * ```php
 * 'components' => [
 *     'authClientCollection' => [
 *         'class' => 'yii\authclient\Collection',
 *         'clients' => [
 *             'yandex' => [
 *                 'class' => 'yii\authclient\clients\Yandex',
 *                 'clientId' => 'yandex_client_id',
 *                 'clientSecret' => 'yandex_client_secret',
 *             ],
 *         ],
 *     ]
 *     // ...
 * ]
 * ```
 *
 * @see https://oauth.yandex.ru/client/new
 * @see http://api.yandex.ru/login/doc/dg/reference/response.xml
 */
class Yandex \yii\authclient\clients\Yandex implements IAuth
{
    /**
     * @inheritdoc
     */
    public $authUrl = 'https://oauth.yandex.ru/authorize';
    /**
     * @inheritdoc
     */
    public $tokenUrl = 'https://oauth.yandex.ru/token';
    /**
     * @inheritdoc
     */
    public $apiBaseUrl = 'https://login.yandex.ru';


    /**
     * @inheritdoc
     */
    protected function initUserAttributes()
    {
        return $this->api('info', 'GET');
    }

    /**
     * @inheritdoc
     */
    public function applyAccessTokenToRequest($request, $accessToken)
    {
        $data = $request->getData();
        if (!isset($data['format'])) {
            $data['format'] = 'json';
        }
        $data['oauth_token'] = $accessToken->getToken();
        $request->setData($data);
    }

    /**
     * @inheritdoc
     */
    protected function defaultName()
    {
        return 'yandex';
    }

    /**
     * @inheritdoc
     */
    protected function defaultTitle()
    {
        return 'Yandex';
    }
}
