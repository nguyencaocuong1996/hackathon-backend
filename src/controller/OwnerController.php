<?php
/**
 * Created by PhpStorm.
 * User: WINDNCC
 * Date: 8/25/2017
 * Time: 3:00 PM
 */

namespace fooco\controller;


use fooco\database\model\owners\Owner;
use football\database\model\owners\OwnerManagement;

class OwnerController
{
    private static $ownerManagement;
    function __construct()
    {
        if (self::$ownerManagement == null)
            self::$ownerManagement = new OwnerManagement();
    }

    function createOwner(array $ownerData) : bool {
        $owner  = new Owner($ownerData);
        self::$ownerManagement->save($owner);
        return (self::$ownerManagement->isInserted());
    }
}