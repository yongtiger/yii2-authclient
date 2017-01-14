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
 * VKontakte OAuth2
 *
 * In order to use VKontakte OAuth2 you must register your application at <http://vk.com/editapp?act=create>.
 *
 * Example application configuration:
 *
 * ```php
 * 'components' => [
 *     'authClientCollection' => [
 *         'class' => 'yii\authclient\Collection',
 *         'clients' => [
 *             'vkontakte' => [
 *                 'class' => 'yii\authclient\clients\VKontakte',
 *                 'clientId' => 'vkontakte_client_id',
 *                 'clientSecret' => 'vkontakte_client_secret',
 *             ],
 *         ],
 *     ]
 *     ...
 * ]
 * ```
 *
 * @see http://vk.com/editapp?act=create
 * @see http://vk.com/developers.php?oid=-1&p=users.get
 */
class VKontakte \yii\authclient\clients\VKontakte implements IAuth
{
    /**
     * @inheritdoc
     */
    public $authUrl = 'https://oauth.vk.com/authorize';
    /**
     * @inheritdoc
     */
    public $tokenUrl = 'https://oauth.vk.com/access_token';
    /**
     * @inheritdoc
     */
    public $apiBaseUrl = 'https://api.vk.com/method';
    /**
     * @var array list of attribute names, which should be requested from API to initialize user attributes.
     * @since 2.0.4
     */
    public $attributeNames = [
        'uid',
        'first_name',
        'last_name',
        'nickname',
        'screen_name',
        'sex',
        'bdate',
        'city',
        'country',
        'timezone',
        'photo'
    ];


    /**
     * @inheritdoc
     */
    protected function initUserAttributes()
    {
        $response = $this->api('users.get.json', 'GET', [
            'fields' => implode(',', $this->attributeNames),
        ]);
        $attributes = array_shift($response['response']);

        $accessToken = $this->getAccessToken();
        if (is_object($accessToken)) {
            $accessTokenParams = $accessToken->getParams();
            unset($accessTokenParams['access_token']);
            unset($accessTokenParams['expires_in']);
            $attributes = array_merge($accessTokenParams, $attributes);
        }

        return $attributes;
    }

    /**
     * @inheritdoc
     */
    public function applyAccessTokenToRequest($request, $accessToken)
    {
        $data = $request->getData();
        $data['uids'] = $accessToken->getParam('user_id');
        $data['access_token'] = $accessToken->getToken();
        $request->setData($data);
    }

    /**
     * @inheritdoc
     */
    protected function defaultName()
    {
        return 'vkontakte';
    }

    /**
     * @inheritdoc
     */
    protected function defaultTitle()
    {
        return 'VKontakte';
    }

    /**
     * @inheritdoc
     */
    protected function defaultNormalizeUserAttributeMap()
    {
        return [
            'id' => 'uid'
        ];
    }
}
