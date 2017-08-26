<?php

/**
 * Created by PhpStorm.
 * User: WINDNCC
 * Date: 4/11/2017
 * Time: 10:32 AM
 */
namespace fooco\database\model;
use Exception;
use fooco\database\DB;
use wind\database\Record;

/**
 * @property array _tableFields
 */
class Service extends Record
{
    function __construct(array $recordData = null)
    {
        $this->_tableFields = array(
            DB::COL_SERVICE_ID,
            DB::COL_SERVICE_NAME,
            DB::COL_ADDRESS_ID,
            DB::COL_SERVICE_TYPE_ID
        );
        parent::__construct($recordData);
    }
    
    public static function addOrder(array $orderData) : bool
    {
        global $db;
        $orderId = $db->insert(DB::TABLE_PITCH_ORDER, $orderData);
        if ($orderId > 0){
            return true;
        } else {
            throw new Exception("Không thể đặt sân!");
        }
    }
}