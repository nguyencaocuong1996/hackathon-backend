<?php
/**
 * Created by PhpStorm.
 * User: WINDNCC
 * Date: 8/26/2017
 * Time: 11:21 AM
 */

namespace fooco\controller;


use fooco\database\DB;
use fooco\database\model\user\User;
use fooco\database\model\user\UserManagement;

class UserController
{
    private static $_instance;

    /**
     * @return mixed
     */
    public static function getInstance() : UserController
    {
        if (self::$_instance == null) self::$_instance = new UserController();
        return self::$_instance;
    }

    private static $userManagement;
    function __construct()
    {
        if (self::$userManagement == null)
            self::$userManagement = new UserManagement();
    }

    function createUser(array $ownerData) : array {
        $user  = new User($ownerData);
        $user->{DB::COL_USER_PASSWORD} = md5($user->{DB::COL_USER_PASSWORD});
        self::$userManagement->save($user);
        if (self::$userManagement->isInserted()){
            return $user->toArray();
        }
        return array();
    }

    function login(array $loginData) : array {
        $userName = $loginData["userName"];
        $userPass = $loginData["userPassword"];
        return self::$userManagement->login($userName, $userPass);
    }
}