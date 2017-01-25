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
 * Oauth Interface
 *
 * Sample data:
 *
 * ```php
    {
        "provider": "live", 
        "openid": "ab30d9e58b344caa", 
        "email": "tigeryang.brainbook@outlook.com", 
        "fullname": "tiger yang", 
        "firstname": "tiger", 
        "lastname": "yang", 
        "gender": "male", 
        "language": "en_US", 
        "avatar": "https://apis.live.net/v5.0/ab30d9e58b344caa/picture?type=large", 
        "link": "https://profile.live.com/", 
    }
 * ```
 *
 * Sample usage:
 *
 * ```php
    public function connectCallback(\yongtiger\authclient\clients\IAuth $client)
    {
        ///First time to call `getUserAttributes()`, only return the basic attrabutes info for login, such as openid.
        echo "<pre>";print_r($client->getUserAttributes());echo "</pre>";
        echo "<pre>";print_r($client->provider);echo "</pre>";
        echo "<pre>";print_r($client->openid);echo "</pre>";

        ///If `$attribute` is not exist in the basic user attrabutes, call `initUserInfoAttributes()` and merge the results into the basic user attrabutes.
        echo "<pre>";print_r($client->email);echo "</pre>";

        ///After calling `initUserInfoAttributes()`, will return all user attrabutes.
        echo "<pre>";print_r($client->getUserAttributes());echo "</pre>";
        echo "<pre>";print_r($client->fullName);echo "</pre>";
        echo "<pre>";print_r($client->firstName);echo "</pre>";
        echo "<pre>";print_r($client->lastName);echo "</pre>";
        echo "<pre>";print_r($client->language);echo "</pre>";
        echo "<pre>";print_r($client->gender);echo "</pre>";
        echo "<pre>";print_r($client->avatar);echo "</pre>";
        echo "<pre>";print_r($client->link);echo "</pre>";
        
        ///Get all user infos at once.
        echo "<pre>";print_r($client->getUserInfos());echo "</pre>";
        exit;
        // ...
    }
 * ```
 *
 */

interface IAuth extends \yii\authclient\ClientInterface
{
    const GENDER_MALE = 1;
    const GENDER_FEMALE = 0;

    /**
     * @return string
     */
    public function getProvider();

    /**
     * Note: You can not use getId() because it conflicts with getId() of BaseClient!
     *
     * @return string
     */
    public function getOpenid();

    /**
     * @return string
     */
    public function getEmail();
    
    /**
     * Note: You can not use getName() because it conflicts with getName() of BaseClient!
     * In addition, do not recommend using getUsername(), because some clients has no username, but only fullname (firstname/lastname or givenname/familyname).
     * If you want to get username, you can use fullname conversion derived (e.g. convert to lowercase, remove or replace blank space with an underscore).
     *
     * @return string|null
     */
    public function getFullname();
    
    /**
     * @return string|null
     */
    public function getFirstname();

    /**
     * @return string|null
     */
    public function getLastname();
    
    /**
     * @return const|null
     */
    public function getGender();

    /**
     * @return string|null
     */
    public function getLanguage();
    
    /**
     * @return string|null
     */
    public function getAvatar();
    
    /**
     * @return string|null
     */
    public function getLink();

    /**
     * @return array|null
     */
    public function getUserInfos();

}