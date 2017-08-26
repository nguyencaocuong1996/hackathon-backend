<?php
/**
 * Created by PhpStorm.
 * User: WINDNCC
 * Date: 8/25/2017
 * Time: 3:00 PM
 */

namespace fooco\controller;


use fooco\database\DB;
use fooco\database\model\owners\Owner;
use football\database\model\owners\OwnerManagement;

class OwnerController
{
    private static $_instance;

    /**
     * @return mixed
     */
    public static function getInstance() : OwnerController
    {
        if (self::$_instance == null) self::$_instance = new OwnerController();
        return self::$_instance;
    }

    private static $ownerManagement;
    function __construct()
    {
        if (self::$ownerManagement == null)
            self::$ownerManagement = new OwnerManagement();
    }

    function createOwner(array $ownerData) : array {
        $owner  = new Owner($ownerData);
        $owner->{DB::COL_OWNER_PASSWORD} = md5($owner->{DB::COL_OWNER_PASSWORD});
        self::$ownerManagement->save($owner);
        if (self::$ownerManagement->isInserted()){
            return $owner->toArray();
        }
        return array();
    }

    function login(array $loginData) : array
    {
        $userName = $loginData[DB::COL_OWNER_USER_NAME];
        $password = $loginData[DB::COL_OWNER_PASSWORD];
        $owner = self::$ownerManagement->login($userName, $password);
        if (!empty($owner)){
            return $owner;
        };
        return array();
    }
}