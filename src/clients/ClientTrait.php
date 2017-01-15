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
 * Trait ClientTrait
 *
 * Used of client classes which implement the interface IAuth.
 * 
 * @package yongtiger\authclient\clients
 */
trait ClientTrait
{
    /**
     * @inheritdoc
     */
    public function getUid()
    {
        return isset($this->getUserAttributes()['uid']) ? $this->getUserAttributes()['uid'] : null;
    }

    /**
     * @inheritdoc
     */
    public function getEmail()
    {
        return isset($this->getUserAttributes()['email']) ? $this->getUserAttributes()['email'] : null;
    }

    /**
     * @inheritdoc
     */
    public function getFullname()
    {
        return isset($this->getUserAttributes()['fullname']) ? $this->getUserAttributes()['fullname'] : null;
    }

    /**
     * @inheritdoc
     */
    public function getFirstname()
    {
        return isset($this->getUserAttributes()['firstname']) ? $this->getUserAttributes()['firstname'] : null;
    }

    /**
     * @inheritdoc
     */
    public function getLastname()
    {
        return isset($this->getUserAttributes()['lastname']) ? $this->getUserAttributes()['lastname'] : null;
    }

    /**
     * @inheritdoc
     */
    public function getGender()
    {
        return isset($this->getUserAttributes()['gender']) ? $this->getUserAttributes()['gender'] : null;
    }

    /**
     * @inheritdoc
     */
    public function getLanguage()
    {
        return isset($this->getUserAttributes()['language']) ? $this->getUserAttributes()['language'] : null;
    }
    /**
     * @inheritdoc
     */
    public function getAvatarUrl()
    {
        return isset($this->getUserAttributes()['avatarUrl']) ? $this->getUserAttributes()['avatarUrl'] : null;
    }

    /**
     * @inheritdoc
     */
    public function getLinkUrl()
    {
        return isset($this->getUserAttributes()['linkUrl']) ? $this->getUserAttributes()['linkUrl'] : null;
    }

}