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
 */
interface IAuth extends \yii\authclient\ClientInterface
{
    /**
     * @return string|null
     */
    public function getEmail();
    
    /**
     * Note: You can not use getName() because it conflicts with getName() of BaseClient!
     * In addition, do not recommend using getUsername(), because some clients no username, but only fullname (firstname/lastname or givenname/familyname).
     * If you want to get username, you can use fullname conversion derived (remove the space, and converted to lowercase).
     *
     * @return string|null
     */
    public function getFullName();
    
    /**
     * @return string|null
     */
    public function getAvatarUrl();
}