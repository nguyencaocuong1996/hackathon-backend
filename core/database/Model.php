<?php
/**
 * Created by PhpStorm.
 * User: WINDNCC
 * Date: 4/2/2017
 * Time: 9:13 AM
 */

namespace wind\database;

use Exception;
use fooco\database\DB;

abstract class Model implements IModelAction
{
    //Đối tượng kết nối với database
    protected $_connection;

    //tham thiếu đến table nào trong database
    protected $_table; // 'players'

    //Mảng chứa các join table và field của các table đó
    protected $_joinTables; //array('users'=>array('userId'));

    //Mảng chứa các khóa chính của model này
    protected $_primaryFields = array();

    //Mảng chứa các cột mà nội dung không được trùng lặp
    protected $_uniqueFields = array();

    //Mảng chứa các cột mà nột dung không được null
    protected $_notNullFields = array();

    // = true nếu khóa chính tự tăng mỗi khi insert (Auto increment)
    protected $_AI = false;

    //Mảng chứa tất cả thuộc tính của table
    protected $_tableFields = array();

    //Mảng chứa các thuộc tính mà sẽ được get ra
    protected $_properties = array();

    //Kiểm tra trạng thái insert hoặc update sau khi gọi hàm save();
    private $_inserted = false;
    private $_updated = false;
    //Kiểm tra trạng thái khi lấy dữ liệu bằng hàm get_
    protected $_getSuccess = false;
    function __construct()
    {
        $this->_connection = MysqliDb::getInstance();
        if ($this->_connection == NULL) {
            $this->_connection = new MysqliDb(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        }
    }

    function getOne($getByProperty, $value): array
    {
        $this->_getSuccess = false;
        //Nếu thuộc tính chỉ định là primary key hoặc unique thì mới cho lấy
        if (in_array($getByProperty, $this->_uniqueFields) || in_array($getByProperty, $this->_primaryFields)) {

            //Nếu tồn tại joinTable và joinTable là mảng thì thêm JOIN vào Query
            if (isset($this->_joinTables) && is_array($this->_joinTables)) {
                //Duyệt mảng _joinTables
                foreach ($this->_joinTables as $joinTable => $joinFields) {
                    //Nếu joinFields của _joinTables đang duyệt là mảng
                    if (is_array($joinFields)) {
                        $existsGetPropertiesInJoinTable = false;
                        //Nếu join ON field có dạng 'tableName.fieldName'
                        if (strpos($joinFields[ 'on' ], '.') !== false) {
                            $field = explode('.', $joinFields[ 'on' ]);
                            $joinCond = $joinTable . '.' . $field[ 1 ] . ' = ' . $joinFields[ 'on' ];
                            //Chuyển join On thành dạng 'fieldName'
                            $joinFields[ 'on' ] = $field[ 1 ];
                        } else {
                            $joinCond = $joinTable . '.' . $joinFields[ 'on' ] . ' = ' . $this->_table . '.' . $joinFields[ 'on' ];
                        }
                        //Duyệt mảng các _properties cần lấy
                        foreach (array_keys($this->_properties) as $property) {
                            //Nếu tồn tại 1 property cần lấy trong joinFields nhưng property đó không tồn tại trong table này
                            if (in_array($property, $joinFields) && !in_array($property, $this->_tableFields)) {
                                $existsGetPropertiesInJoinTable = true;
                            }
                        }
                        //Nếu có thì thêm join vào query
                        if ($existsGetPropertiesInJoinTable) {
                            $this->_connection->join($joinTable, $joinCond);
                        }
                    } else {
                        throw new Exception("Định dạng của joinTable chưa đúng!!!");
                    }
                }
            }
            $this->_connection->where($this->_table . '.' . $getByProperty, $value);
            $results = $this->_connection->getOne($this->_table, DB::buildGetProperties($this->_properties, $this->_table));
            if (is_null($results)) throw new Exception("Not found $getByProperty = $value");
            $this->_getSuccess = true;
            return $results;
        } else {
            throw new Exception("Thuộc tính chỉ định phải là primary hoặc unique.");
        }
    }

    /**
     * @param array $properties
     */
    public function setProperties($properties = null)
    {
        if (empty($properties) || !isset($properties)){
            throw new Exception('Thuộc tính cần lấy không được để trống');
        }
        if (is_string($properties)) {
            $properties = explode(',', $properties);
            $properties = array_flip($properties);
            $properties = array_map(function () {
                return '';
            }, $properties);
        }
        $this->_properties = $properties;
        return $this;
    }

    public function save(&$record): Record
    {
        try {
            if ($record instanceof Record) {
                if ($this->isRecordExists($record)) {
                    $this->_update($record);
                } else {
                    $this->_insert($record);
                }
            $record->setExists(true);
            }
        } catch (Exception $exception) {
            throw $exception;
        }
        return $record;
    }

    public function _insert(&$record)
    {
        $this->_inserted = false;
        if ($record instanceof Record) {
            $recordProperties = $record->getProperties();
            //Kiểm tra nếu có field yêu cầu notNull trong Table
            if (! empty($this->_notNullFields)) {
                $notNullProperties = array_intersect($recordProperties, $this->_notNullFields);
                if (! empty(array_diff($this->_notNullFields, $notNullProperties))) {
                    throw new Exception("Lỗi khi insert: Thuộc tính notNull không tìm thấy hoặc không đầy đủ trong Record này!");
                }
            }
            $recordData = $record->toArray();
            //Kiểm tra nếu Table có primaryKey
            if (! empty($this->_primaryFields)) {
                //Nếu primaryKey là tự động tăng thì loại bỏ primaryKey khỏi Record trước khi insert
                if ($this->_AI) {
                    $this->_removeAIPrimaryKeys($recordData);
                } else {
                    //Kiểm tra xem record có đủ primaryKey mà table yêu cầu hay chưa
                    $primaryProperties = array_intersect($recordProperties, $this->_primaryFields);
                    if (! empty(array_diff($this->_primaryFields, $primaryProperties))) {
                        throw new Exception("Lỗi khi insert: Thuộc tính primary notAI không tìm thấy hoặc không đầy đủ trong Record này!");
                    }
                }
            }
            if ($this->isRecordExists($record)) {
                throw new Exception("Lỗi khi insert: Record hoặc 1 trong các thuộc tính unique của Record đã tồn tại trong $this->_table!");
            }
            $this->_connection->startTransaction();
            $insertId = $this->_connection->insert($this->_table, $recordData);
            if ($insertId) {
                if ($this->_AI) {
                    $record->{$this->_primaryFields[ 0 ]} = $insertId;
                }
                $this->_connection->commit();
                $this->_inserted = true;
            } else {
                $this->_connection->rollback();
                throw new Exception("Lỗi khi insert: Lỗi không xác định!");
            }
        }
        return false;
    }

    public function _update(&$record): bool
    {
        $this->_updated = false;
        if ($record instanceof Record) {
            if ($this->isRecordExists($record)) {
                $recordData = $record->toArray();
                if ($this->_AI) {
                    $this->_removeAIPrimaryKeys($recordData);
                }
//                var_dump($recordData);
                if (! $this->isPrimaryKeyExists($record)) {
                    throw new Exception("Lỗi update: Không tìm thấy thuộc tính khóa để xác định Record khi update!!!");
                } else {
                    foreach ($this->_primaryFields as $property) {
                        $this->_connection->where($property, $record->{$property});
                    }
                }
                $updateStatus = $this->_connection->update($this->_table, $recordData);
                if ($this->_connection->count <= 0 || $updateStatus === false) {
                    throw new Exception("Lỗi update: Không tìm thấy Record nào để update hoặc Record không có gì thay đổi hoặc 1 trong các thuộc tính Unique đã tồn tại!");
//                        throw new Exception($this->_connection->getLastError());
                } else {
                    $this->_updated = true;
                    return true;
                }
            } else {
                throw new Exception("Lỗi update: Record này không tồn tại trong Table $this->_table");
            }
        }
        return false;
    }

    function isRecordExists($record): bool
    {
        $primaryKeys = array_intersect($record->{'_properties'}, $this->_primaryFields);
        $uniqueFields = array_intersect($record->{'_properties'}, $this->_uniqueFields);
        $uniqueFields = array_merge($primaryKeys, $uniqueFields);
        if (empty($uniqueFields)){
            throw new Exception("Thông tin cung cấp không đủ để xác định Record có tồn tại hay không, ít nhất phải có 1 unique field!");
        }
        foreach ($uniqueFields as $uniqueField) {
            $this->_connection->orWhere($uniqueField, $record->{$uniqueField});
        }
        return $this->_connection->getValue($this->_table, 'count(*)') > 0;
    }

    function isPrimaryKeyExists($record)
    {
        $primaryKeys = array_intersect($this->_primaryFields, $record->{'_properties'});
        if (empty(array_diff($this->_primaryFields, $primaryKeys))) {
            return true;
        } else {
            return false;
        }
    }

    private function _removeAIPrimaryKeys(array &$recordData)
    {
        $recordData = array_filter($recordData, function ($key) {
            if (! in_array($key, $this->_primaryFields)) {
                return $key;

            } else {
                return null;
            }
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * @return bool
     */
    public function isInserted(): bool
    {
        return $this->_inserted;
    }

    /**
     * @return bool
     */
    public function isUpdated(): bool
    {
        return $this->_updated;
    }

    public function isValueExists($key, $value): bool
    {
        $this->_connection->where($key, $value);
        return $this->_connection->getValue($this->_table, 'count(*)') > 0;
    }

    public function delete($record): bool
    {
        return false;
    }

    /**
     * @return array
     */
    public function getUniqueFields(): array
    {
        return array_merge($this->_uniqueFields, $this->_primaryFields);
    }

    public function getExistsUniqueErrors(Record $record, array $errorMessages = null): array
    {
        $errors = array();
        $uniqueFields = $this->getUniqueFields();
        foreach ($record->toArray() as $properties => $value) {
            if (in_array($properties, $uniqueFields)) {
                if ($this->isValueExists($properties, $value)) {
                    $errors[ $properties ] = "$value đã tồn tại!";
                    if (isset($errorMessages) && ! empty($errorMessages)) {
                        if (in_array($properties, array_keys($errorMessages))) {
                            $errors[ $properties ] = $errorMessages[ $properties ];
                        }
                    }
                }
            }
        }
        return $errors;
    }

    /**
     * @return array
     */
    public function getPrimaryFields(): array
    {
        return $this->_primaryFields;
    }

    /**
     * @return mixed
     */
    public function getTable()
    {
        return $this->_table;
    }

    /**
     * @return bool
     */
    public function isGetSuccess(): bool
    {
        return $this->_getSuccess;
    }
}