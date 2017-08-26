<?php
/**
 * Created by PhpStorm.
 * User: WINDNCC
 * Date: 4/25/2017
 * Time: 11:26 PM
 */

namespace fooco\database\model\location;


use fooco\database\DB;
use wind\database\RecordCollection;

class LocationCollection extends RecordCollection
{
    protected static $_table;
    function __construct($properties)
    {
        self::$_table = DB::TABLE_LOCATION;
        $this->checkGetProperties($properties);
        $this->_properties = DB::buildGetProperties($properties, self::$_table);
        $this->get();
    }

    public function getByParentId($parentId = null) : LocationCollection
    {
        global $db;
        if ($parentId == null){
            $db->where(DB::COL_LOCATION_PARENT, NULL, "IS");
        } else {
            $db->where(DB::COL_LOCATION_PARENT, $parentId);
        }
        $this->_collection = $db->get(self::$_table, null, $this->_properties);
        return $this;
    }
    public function get() : LocationCollection
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