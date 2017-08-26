<?php

/**
 * Created by PhpStorm.
 * User: WINDNCC
 * Date: 4/11/2017
 * Time: 10:08 AM
 */
namespace fooco\database\model\owners;
use fooco\database\DB;
use wind\database\Record;

/**
 * @property array _tableFields
 */
class Owner extends Record
{
    function __construct(array $ownerData = null)
    {
        $this->_tableFields = array(
            DB::COL_OWNER_ID,
            DB::COL_OWNER_EMAIL,
            DB::COL_OWNER_USER_NAME,
            DB::COL_OWNER_PASSWORD,
            DB::COL_OWNER_PHONE
        );
        parent::__construct($ownerData);
    }
}