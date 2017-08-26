<?php

/**
 * Created by PhpStorm.
 * User: WINDNCC
 * Date: 4/23/2017
 * Time: 2:56 PM
 */
namespace fooco\database\model\follow;
use fooco\database\DB;
use wind\database\Record;

/**
 * @property array _tableFields
 */
class Follow extends Record
{
    function __construct(array $recordData = null)
    {
        $this->_tableFields = array(
            DB::COL_USER_ID,
            DB::COL_USER_FOLLOW_ID
        );
        parent::__construct($recordData);
    }

    function __toString()
    {
        if (isset($this->{DB::COL_LOCATION_PARENT})){
            $parent = AddressManagement::getInstance()->getById($this->{DB::COL_LOCATION_PARENT});
            return $this->{DB::COL_LOCATION_NAME}.', '.$parent;
        }
        return $this->{DB::COL_LOCATION_NAME};
    }

}