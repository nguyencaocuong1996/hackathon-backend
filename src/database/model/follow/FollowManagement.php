<?php

/**
 * Created by PhpStorm.
 * User: WINDNCC
 * Date: 4/23/2017
 * Time: 2:56 PM
 */

namespace fooco\database\model\follow;

use fooco\database\DB;
use wind\database\Model;

/**
 * @property bool  _AI
 * @property array _notNullFields
 * @property array _uniqueFields
 * @property  string _table
 * @property array _tableFields
 */
class FollowManagement extends Model
{
    private static $_instance;

    function __construct()
    {
        $this->_table = DB::TABLE_FOLLOW;
        $this->_tableFields = array(
            DB::COL_USER_ID,
            DB::COL_USER_FOLLOW_ID
        );
        parent::__construct();
        $this->_primaryFields = [DB::COL_USER_ID, DB::COL_USER_FOLLOW_ID];
        $this->_uniqueFields = [];
        $this->_notNullFields = [];
        $this->_AI = false;
    }

    public static function getInstance() : FollowManagement
    {
        if (self::$_instance === null){
            return new FollowManagement();
        }
        return self::$_instance;
    }
}