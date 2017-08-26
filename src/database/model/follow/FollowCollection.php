<?php
/**
 * Created by PhpStorm.
 * User: WINDNCC
 * Date: 4/25/2017
 * Time: 11:26 PM
 */

namespace fooco\database\model\follow;


use fooco\database\DB;
use wind\database\RecordCollection;

/**
 * @property array|bool|string _properties
 */
class FollowCollection extends RecordCollection
{
    protected static $_table;
    function __construct($properties)
    {
        self::$_table = DB::TABLE_FOLLOW;
        $this->checkGetProperties($properties);
        $this->_properties = DB::buildGetProperties($properties, self::$_table);
    }

    public function getByUserId($userId = 0) : FollowCollection
    {
        global $db;
        if ($userId != 0){
            $joinCond = DB::TABLE_USER . '.' . DB::COL_USER_ID . ' = ' . DB::TABLE_FOLLOW . '.' . DB::COL_USER_FOLLOW_ID;
            $db->join(DB::TABLE_USER, $joinCond, "LEFT");
            $db->where(DB::TABLE_FOLLOW . '.' . DB::COL_USER_ID, $userId);
            $this->_collection = $db->get(self::$_table, null, $this->_properties);
        }
        return $this;
    }

    function checkGetProperties(&$properties)
    {
        // TODO: Implement checkGetProperties() method.
    }
}