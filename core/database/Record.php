<?php
/**
 * Created by PhpStorm.
 * User: WINDNCC
 * Date: 4/2/2017
 * Time: 4:04 PM
 */

namespace wind\database;

use Exception;
use JsonSerializable;

abstract class Record implements JsonSerializable, IRecordAction
{
    protected $_tableFields;
    protected $_properties;
    protected $_exists = false;
    function __construct(array $recordData = null)
    {
        if (is_array($recordData) && isset($recordData)) {
            $this->_properties = array_keys($recordData);
            //Chuyển đổi giá trị từ mảng sang thuộc tính của object
            foreach ($recordData as $key => $value) {
                $this->{$key} = $value;
            }
        } else {
            $this->_properties = [];
        }
    }

    function __get($name)
    {
        if (property_exists($this, $name)) {
            return $this->{$name};
        } else {
            throw new Exception("Thuộc tính $name không tồn tại trong " . get_class($this));
        }
    }

    function __set($name, $value)
    {
        $this->{$name} = $value;
        //Nếu property cần set đã tồn tài trong Record
        if (property_exists($this, $name)) {
            //Và tên property là cột của Table
            if (in_array($name, $this->_tableFields)) {
                //Và name chưa nằm trong _tableFields
                if ($name!== null && $this->_properties!== null){
                    if (!in_array($name,$this->_properties)){
                        $this->_properties[] = $name;
                    }
                }

            }
        }
    }

    function __unset($name)
    {
        if (property_exists($this, $name)) {
            //Và tên property là cột của Table
            if (in_array($name, $this->_tableFields)) {
                unset($this->_properties[ $name ]);
            }
            unset($this->{$name});
        }
    }

    function jsonSerialize()
    {
        $arr = array();
        foreach ($this->_properties as $property) {
            $arr[ $property ] = $this->{$property};
        }
        return $arr;
    }

    function toArray(): array
    {
        return $this->jsonSerialize();
    }

    /**
     * @return array
     */
    public function getProperties(): array
    {
        return $this->_properties;
    }

    /**
     * @return mixed
     */
    public function getTableFields()
    {
        return $this->_tableFields;
    }

    /**
     * @return mixed
     */
    /**
     * @return bool
     */
    public function isExists(): bool
    {
        return $this->_exists;
    }

    /**
     * @param bool $exists
     */
    public function setExists(bool $exists)
    {
        $this->_exists = $exists;
    }

    public function toString() : String
    {
        return '';
    }
}
