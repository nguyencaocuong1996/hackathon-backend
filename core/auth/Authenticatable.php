<?php
/**
 * Created by PhpStorm.
 * User: WINDNCC
 * Date: 4/9/2017
 * Time: 10:57 AM
 */

namespace wind\auth;


trait Authenticatable
{
    protected $_currentAuth;
    public function getAuthId()
    {
        return $this->_currentAuth->{'userId'};
    }

    public function getRememberToken()
    {
        return $this->_currentAuth->{'rememberToken'};
    }

    public function getAuthPassword()
    {
        return $this->_currentAuth->{'userPassword'};
    }

    public function getAccessToken()
    {
        return $this->_currentAuth->{'accessToken'};
    }

    /**
     * @param mixed $currentAuth
     */
    public function setCurrentAuth($currentAuth = null)
    {
        $this->_currentAuth = $currentAuth;
        return $this;
    }

}