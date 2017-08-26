<?php
/**
 * Created by PhpStorm.
 * User: WINDNCC
 * Date: 8/26/2017
 * Time: 11:26 AM
 */

namespace fooco\database\model\user;

use fooco\database\DB;
use wind\database\Record;

/**
 * @property array _tableFields
 */
class User extends Record
{
    function __construct(array $ownerData = null)
    {
        $this->_tableFields = array(
            DB::COL_USER_ID,
            DB::COL_USER_NAME,
            DB::COL_USER_PASSWORD,
            DB::COL_USER_PHONE
        );
        parent::__construct($ownerData);
    }
}