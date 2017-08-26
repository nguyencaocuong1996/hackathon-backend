<?php
/**
 * Created by PhpStorm.
 * User: WINDNCC
 * Date: 4/3/2017
 * Time: 8:53 PM
 */

namespace wind\database;

use fooco\database\DB;
use IteratorAggregate;
use Traversable;
use ArrayIterator;

abstract class RecordCollection implements IteratorAggregate
{
    protected static $_table;
    protected $_collection = array();
    protected $_properties = array();
    protected $_condition = array();
    function add(Record $record)
    {
        array_push($this->_collection, $record);
    }

    function pop() : Record
    {
        return array_pop($this->_collection);
    }
    function shift() : Record
    {
        return array_shift($this->_collection);
    }

    /**
     * Retrieve an external iterator
     *
     * @link  http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     * @since 5.0.0
     */
    public function getIterator()
    {
        return new ArrayIterator($this->_collection);
    }

    /**
     * @return array
     */
    public function getProperties(): array
    {
        return $this->_properties;
    }

    /**
     * @param array $properties
     */
    public function setProperties(array $properties)
    {
//        $this->_properties = $properties;
        $this->_properties = DB::buildGetProperties($properties, self::$_table);
    }

    public function toArray()
    {
        return $this->_collection;
    }
    abstract function checkGetProperties(&$properties);

    function buildTree(int $parent = null, array $a = array(), string $idColumn = '', string $parentColumn = '') : array
    {
        $arr = array();
        if (!empty($a)){
            foreach ($a as $key=>$value){
                if ($value[$parentColumn] === $parent){
                    unset($a[$key]);
                    $value['child'] = $this->buildTree($value[$idColumn], $a, $idColumn, $parentColumn);
                    if (empty($value['child'])){
                        unset($value['child']);
                    }
                    $arr[] = $value;
                }
            }
        } else {
            exit();
        }
        return $arr;
    }
}