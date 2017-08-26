<?php
/**
 * Created by PhpStorm.
 * User: WINDNCC
 * Date: 8/26/2017
 * Time: 6:52 PM
 */

namespace fooco\controller;


use fooco\database\DB;
use fooco\database\model\follow\Follow;
use fooco\database\model\follow\FollowCollection;
use fooco\database\model\follow\FollowManagement;

class FollowController
{
    private static $_instance;

    /**
     * @return mixed
     */
    private static $followManagement;
    public function __construct()
    {
        self::$followManagement = FollowManagement::getInstance();
    }

    public static function getInstance() :FollowController
    {
        if (self::$_instance == null) self::$_instance = new FollowController();
        return self::$_instance;
    }

    function follow($followData) : bool
    {
            global $db;
            $dataIns = array(
                DB::COL_USER_ID=>$followData[DB::COL_USER_ID],
                DB::COL_USER_FOLLOW_ID=>$followData[DB::COL_USER_FOLLOW_ID]
            );
            return $db->insert(DB::TABLE_FOLLOW, $dataIns);
    }

    function getFollowByUserId($userId, $props) : array
    {
        $followCollection = new FollowCollection($props);
        $followCollection->getByUserId($userId);
        return $followCollection->toArray();
    }
}