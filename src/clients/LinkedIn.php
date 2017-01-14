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
use Yii;

/**
 * LinkedIn OAuth2
 *
 * In order to use linkedIn OAuth2 you must register your application at <https://www.linkedin.com/secure/developer>.
 *
 * Example application configuration:
 *
 * ```php
 * 'components' => [
 *     'authClientCollection' => [
 *         'class' => 'yii\authclient\Collection',
 *         'clients' => [
 *             'linkedin' => [
 *                 'class' => 'yii\authclient\clients\LinkedIn',
 *                 'clientId' => 'linkedin_client_id',
 *                 'clientSecret' => 'linkedin_client_secret',
 *             ],
 *         ],
 *     ]
 *     ...
 * ]
 * ```
 *
 * @see http://developer.linkedin.com/documents/authentication
 * @see https://www.linkedin.com/secure/developer
 * @see http://developer.linkedin.com/apis
 */
class LinkedIn \yii\authclient\clients\LinkedIn implements IAuth
{
    /**
     * @inheritdoc
     */
    public $authUrl = 'https://www.linkedin.com/uas/oauth2/authorization';
    /**
     * @inheritdoc
     */
    public $tokenUrl = 'https://www.linkedin.com/uas/oauth2/accessToken';
    /**
     * @inheritdoc
     */
    public $apiBaseUrl = 'https://api.linkedin.com/v1';
    /**
     * @var array list of attribute names, which should be requested from API to initialize user attributes.
     * @since 2.0.4
     */
    public $attributeNames = [
        'id',
        'email-address',
        'first-name',
        'last-name',
        'public-profile-url',
    ];


    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if ($this->scope === null) {
            $this->scope = implode(' ', [
                'r_basicprofile',
                'r_emailaddress',
            ]);
        }
    }

    /**
     * @inheritdoc
     */
    protected function defaultNormalizeUserAttributeMap()
    {
        return [
            'email' => 'email-address',
            'first_name' => 'first-name',
            'last_name' => 'last-name',
        ];
    }

    /**
     * @inheritdoc
     */
    protected function initUserAttributes()
    {
        return $this->api('people/~:(' . implode(',', $this->attributeNames) . ')', 'GET');
    }

    /**
     * @inheritdoc
     */
    public function applyAccessTokenToRequest($request, $accessToken)
    {
        $data = $request->getData();
        $data['oauth2_access_token'] = $accessToken->getToken();
        $request->setData($data);
    }

    /**
     * @inheritdoc
     */
    protected function defaultName()
    {
        return 'linkedin';
    }

    /**
     * @inheritdoc
     */
    protected function defaultTitle()
    {
        return 'LinkedIn';
    }
}
