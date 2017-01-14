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
 * Twitter OAuth2
 *
 * In order to use Twitter OAuth2 you must register your application at <https://dev.twitter.com/apps/new>.
 *
 * Example application configuration:
 *
 * ```php
 * 'components' => [
 *     'authClientCollection' => [
 *         'class' => 'yii\authclient\Collection',
 *         'clients' => [
 *             'twitter' => [
 *                 'class' => 'yii\authclient\clients\Twitter',
 *                 'attributeParams' => [
 *                     'include_email' => 'true'
 *                 ],
 *                 'consumerKey' => 'twitter_consumer_key',
 *                 'consumerSecret' => 'twitter_consumer_secret',
 *             ],
 *         ],
 *     ]
 *     ...
 * ]
 * ```
 *
 * @see https://dev.twitter.com/apps/new
 * @see https://dev.twitter.com/docs/api
 */
class Twitter \yii\authclient\clients\Twitter implements IAuth
{
    /**
     * @inheritdoc
     */
    public $authUrl = 'https://api.twitter.com/oauth/authenticate';
    /**
     * @inheritdoc
     */
    public $requestTokenUrl = 'https://api.twitter.com/oauth/request_token';
    /**
     * @inheritdoc
     */
    public $requestTokenMethod = 'POST';
    /**
     * @inheritdoc
     */
    public $accessTokenUrl = 'https://api.twitter.com/oauth/access_token';
    /**
     * @inheritdoc
     */
    public $accessTokenMethod = 'POST';
    /**
     * @inheritdoc
     */
    public $apiBaseUrl = 'https://api.twitter.com/1.1';
    /**
     * @var array list of extra parameters, which should be used, while requesting user attributes from Twitter API.
     * For example:
     *
     * ```php
     * [
     *     'include_email' => 'true'
     * ]
     * ```
     *
     * @see https://dev.twitter.com/rest/reference/get/account/verify_credentials
     * @since 2.0.6
     */
    public $attributeParams = [];


    /**
     * @inheritdoc
     */
    protected function initUserAttributes()
    {
        return $this->api('account/verify_credentials.json', 'GET', $this->attributeParams);
    }

    /**
     * @inheritdoc
     */
    protected function defaultName()
    {
        return 'twitter';
    }

    /**
     * @inheritdoc
     */
    protected function defaultTitle()
    {
        return 'Twitter';
    }
}
