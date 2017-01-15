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
 * Sample date:
 * ```
    {
       "id": "ab30d9e58b344caa", 
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
 */

interface IAuth extends \yii\authclient\ClientInterface
{
    const GENDER_MALE = 1;
    const GENDER_FEMALE = 0;

    /**
     * Note: You can not use getId() because it conflicts with getId() of BaseClient!
     *
     * @return string|null
     */
    public function getUid();

    /**
     * @return string|null
     */
    public function getEmail();
    
    /**
     * Note: You can not use getName() because it conflicts with getName() of BaseClient!
     * In addition, do not recommend using getUsername(), because some clients no username, but only fullname (firstname/lastname or givenname/familyname).
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
     * @return string|null
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