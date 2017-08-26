<?php
/**
 * Created by PhpStorm.
 * User: WINDNCC
 * Date: 4/25/2017
 * Time: 11:26 PM
 */

namespace fooco\database\model\review;


use fooco\database\DB;
use wind\database\RecordCollection;

/**
 * @property array|bool|string _properties
 */
class ReviewCollection extends RecordCollection
{
    protected static $_table;
    function __construct($properties)
    {
        self::$_table = DB::TABLE_REVIEW;
        $this->checkGetProperties($properties);
        $this->_properties = DB::buildGetProperties($properties, self::$_table);
//        $this->_properties = "users.userName, reviews.reviewContent";
//        $this->get();
    }

    public function getByServiceId($serviceId = 0) : ReviewCollection
    {
        global $db;
        $db->where(DB::TABLE_REVIEW . '.' . DB::COL_SERVICE_ID, $serviceId);
        $joinCond = DB::TABLE_USER.'.'.DB::COL_USER_ID . ' = ' . DB::TABLE_REVIEW . '.' . DB::COL_USER_ID;
        $db->join(DB::TABLE_USER . ' ', $joinCond, 'LEFT');
        $this->_collection = $db->get(self::$_table, null, $this->_properties);
        echo $db->getLastQuery();
        return $this;
    }
    public function get() : ReviewCollection
    {
        global $db;
        $this->_collection = $db->get(self::$_table, null, $this->_properties);
        return $this;
    }
    function buildTree(int $parent = null, array $a = [], string $idColumn = DB::COL_LOCATION_ID, string $parentColumn = DB::COL_LOCATION_PARENT): array
    {
        return parent::buildTree($parent, $this->_collection, $idColumn, $parentColumn);
    }

    function checkGetProperties(&$properties)
    {
        // TODO: Implement checkGetProperties() method.
    }
}