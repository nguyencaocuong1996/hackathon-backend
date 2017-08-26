<?php
/**
 * Created by PhpStorm.
 * User: WINDNCC
 * Date: 4/9/2017
 * Time: 4:20 PM
 */

namespace wind\auth;


abstract class Auth
{
    protected static $_user;
    public static function isLogin() : bool
    {
        if (isset($_SESSION['remember_token']) || isset($_COOKIE['remember_token'])){
                return true;
        }
        return false;
    }

    public static function getUser()
    {
        return self::$_user;
    }
    public static function setUser($user)
    {
        self::$_user = $user;
    }

    public static function isAuth()
    {
        return isset(self::$_user);
    }

    public static function getRememberToken()
    {
        if (self::isLogin()){
            return $_SESSION['remember_token'];
        }
        return false;
    }

}