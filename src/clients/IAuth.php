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
 * ```php
    {
       "openid": "ab30d9e58b344caa", 
       "email": "tigeryang.brainbook@outlook.com", 
       "fullname": "tiger yang", 
       "firstname": "tiger", 
       "lastname": "yang", 
       "gender": "male", 
       "language": "en_US", 
       "avatarUrl": "https://apis.live.net/v5.0/ab30d9e58b344caa/picture?type=large", 
       "linkUrl": "https://profile.live.com/", 
    }
 * ```
 *
 * Sample usage:
 *
 * ```php
    public function connectCallback(\yongtiger\authclient\clients\IAuth $client)
    {
        // uncomment below to see which attributes you get back
        echo "<pre>";print_r($client->getUserAttributes());echo "</pre>";   ///the first call returns the basic information, including openid, etc.
        echo "<pre>";print_r($client->getUserInfo('email'));echo "</pre>";  ///returns more user info

        echo "<pre>";print_r($client->openid);echo "</pre>";
        echo "<pre>";print_r($client->email);echo "</pre>";
        echo "<pre>";print_r($client->fullName);echo "</pre>";
        echo "<pre>";print_r($client->firstName);echo "</pre>";
        echo "<pre>";print_r($client->lastName);echo "</pre>";
        echo "<pre>";print_r($client->language);echo "</pre>";
        echo "<pre>";print_r($client->gender);echo "</pre>";
        echo "<pre>";print_r($client->avatarUrl);echo "</pre>";
        echo "<pre>";print_r($client->linkUrl);echo "</pre>";
        
        echo "<pre>";print_r($client->getUserAttributes());echo "</pre>";   ///later call returns more user information
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
     * Get user info included extra attribute
     *
     * When there is no specified attribute in getUserAttributes(), try calling this method to get. @see [[trait ClientTrait]]
     *
     * @param string $attribute user extra attribute
     * @return array
     */
    public function getUserInfo($attribute);
    
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
    public function getAvatarUrl();
    
    /**
     * @return string|null
     */
    public function getLinkUrl();

}