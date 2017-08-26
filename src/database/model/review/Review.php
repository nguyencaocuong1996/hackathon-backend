<?php

/**
 * Created by PhpStorm.
 * User: WINDNCC
 * Date: 4/23/2017
 * Time: 2:56 PM
 */
namespace fooco\database\model\review;
use fooco\database\DB;
use wind\database\Record;

/**
 * @property array _tableFields
 */
class Review extends Record
{
    function __construct(array $recordData = null)
    {
        $this->_tableFields = array(
            DB::COL_REVIEW_ID,
            DB::COL_REVIEW_CONTENT,
            DB::COL_REVIEW_STAR,
            DB::COL_REVIEW_NUM,
            DB::COL_REVIEW_STAR_FOR_SERVICE,
            DB::COL_SERVICE_ID,
            DB::COL_USER_ID
        );
        parent::__construct($recordData);
    }

//    function __toString()
//    {
//        if (isset($this->{DB::COL_LOCATION_PARENT})){
//            $parent = AddressManagement::getInstance()->getById($this->{DB::COL_LOCATION_PARENT});
//            return $this->{DB::COL_LOCATION_NAME}.', '.$parent;
//        }
//        return $this->{DB::COL_LOCATION_NAME};
//    }

}