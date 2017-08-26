<?php

/**
 * Created by PhpStorm.
 * User: WINDNCC
 * Date: 4/23/2017
 * Time: 2:56 PM
 */

namespace fooco\database\model\address;

use fooco\database\DB;
use wind\database\Model;

/**
 * @property bool   _AI
 * @property array  _notNullFields
 * @property array  _uniqueFields
 * @property string _table
 * @property array  _tableFields
 */
class AddressManagement extends Model
{
    private static $_instance;
    public static function getInstance() : AddressManagement
    {
        if (self::$_instance == null) self::$_instance = new AddressManagement();
        return self::$_instance;
    }
    function __construct()
    {
        $this->_table = DB::TABLE_ADDRESS;
        $this->_tableFields = array(
            DB::COL_ADDRESS_ID,
            DB::COL_ADDRESS_DETAIL,
            DB::COL_LOCATION_ID,
            DB::COL_LOCATION_LAT,
            DB::COL_LOCATION_LNG
        );
        parent::__construct();
        $this->_primaryFields[] = DB::COL_ADDRESS_ID;
        $this->_uniqueFields = [];
        $this->_notNullFields = [];
        $this->_AI = true;
    }


}