<?php

/**
 * Created by PhpStorm.
 * User: WINDNCC
 * Date: 4/23/2017
 * Time: 2:56 PM
 */

namespace fooco\location;

use fooco\database\DB;
use wind\database\Model;

/**
 * @property bool  _AI
 * @property array _notNullFields
 * @property array _uniqueFields
 * @property  string _table
 * @property array _tableFields
 */
class LocationManagement extends Model
{
    private static $_instance;

    function __construct()
    {
        $this->_table = DB::TABLE_LOCATION;
        $this->_tableFields = array(
            DB::COL_LOCATION_ID,
            DB::COL_LOCATION_NAME,
            DB::COL_LOCATION_PARENT,
        );
        parent::__construct();
        $this->_primaryFields[] = DB::COL_LOCATION_ID;
        $this->_uniqueFields = [];
        $this->_notNullFields = [];
        $this->_AI = true;
    }

    public static function getInstance() : LocationManagement
    {
        if (self::$_instance === null){
            return new LocationManagement();
        }
        return self::$_instance;
    }
}