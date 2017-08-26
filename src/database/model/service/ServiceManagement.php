<?php

/**
 * Created by PhpStorm.
 * User: WINDNCC
 * Date: 4/11/2017
 * Time: 10:32 AM
 */

namespace fooco\database\model\service;

use fooco\database\model\owners\Owner;
use wind\database\Model;
use fooco\database\DB;

/**
 * @property array  _tableFields
 * @property bool   _AI
 * @property string _table
 */
class ServiceManagement extends Model
{
    private static $_instance;

    /**
     * @return mixed
     */
    public static function getInstance() : ServiceManagement
    {
        if (self::$_instance == null) self::$_instance = new ServiceManagement();
        return self::$_instance;
    }

    function __construct()
    {
        parent::__construct();
        $this->_table = DB::TABLE_SERVICE;
        $this->_tableFields = array(
            DB::COL_SERVICE_ID,
            DB::COL_ADDRESS,
            DB::COL_SERVICE_NAME,
            DB::COL_SERVICE_TYPE_ID
        );
        $this->_primaryFields[] = DB::COL_SERVICE_ID;
        $this->_uniqueFields[] = DB::COL_SERVICE_NAME;
        $this->_AI = true;
    }

    public function getByTypeId(int $ownerId = 0) : Owner
    {
        if ($ownerId !== 0){
            $data = $this->getOne(DB::COL_SERVICE_ID, $ownerId);
            return new Owner($data);
        }
        return new Owner([]);
    }

    public function getByOwnerId(int $ownerId)
    {
        
    }
}