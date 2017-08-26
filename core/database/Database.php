<?php
/**
 * Created by PhpStorm.
 * User: WINDNCC
 * Date: 4/4/2017
 * Time: 11:23 PM
 */

namespace wind\database;

use PDO;
use PDOStatement;

abstract class Database implements ITableAction, IDatabaseAction
{
    protected $_connectString;
    public $_connection;
    protected $_stmt;
    /**
     * Properties connect to database
     *
     * @var $_dbHost
     * @var $_dbUser
     * @var $_dbName
     * @var $_dbPassword
     */
    public $host;

    protected $_username;

    protected $_password;

    public $db;

    public $port;

    public $charset;

    public $prefix;


    protected $_query;

    protected $_tableName;

    protected $_lastQuery;

    public $fetchType = 'array'; //array|object|json

    /**
     * queryBuilder property
     *
     * @var $_where array mảng chứa các điều kiện của câu truy vấn
     */
    protected $_where = array();

    protected $_params = array();

    /**
     * Database constructor.
     *
     * @param null   $host
     * @param null   $username
     * @param null   $password
     * @param null   $db
     * @param null   $port
     * @param string $charset
     */
    function __construct($host = null, $username = null, $password = null, $db = null, $port = null, $charset = 'utf8')
    {
        // if params were passed as array
        if ( is_array($host) ) {
            foreach ( $host as $key => $val ) {
                $$key = $val;
            }
        }
        // if host were set as mysqli socket
        if ( is_object($host) ) {
            $this->_mysqli = $host;
        } else {
            $this->host = $host;
        }

        $this->_username = $username;
        $this->_password = $password;
        $this->db = $db;
        $this->port = $port;
        $this->charset = $charset;

        if ( isset($prefix) ) {
            $this->setPrefix($prefix);
        }
    }

    abstract function connect();

    function disconnect()
    {
        if ( ! isset($this->_connection) ) return;
        $this->_connection = null;
    }

    function insert(string $table, array $insertData)
    {
        $this->_query = 'INSERT INTO '.$table;
        $this->_prepareQuery()->execute();
    }

    function update(string $table, array $tableData, int $numRows = null)
    {
        // TODO: Implement update() method.
    }

    /**
     * @param string   $table
     * @param int|null $numRows
     * @param string   $columns
     *
     * @return mixed
     */
    function get(string $table, int $numRows = null, $columns = '*')
    {
        if ( empty($columns) ) {
            $columns = '*';
        }
        $columns = is_array($columns) ? explode(',', $columns) : $columns;
        if ( strpos($table, '.') === false ) {
            $this->_tableName = $this->prefix . $table;
        } else {
            $this->_tableName = $table;
        }
        $this->_query = 'SELECT ' . $columns . ' FROM ' . $this->_tableName;
        $this->_prepareQuery()->execute();
        return $this->_dynamicBindResults();
    }

    function delete(string $table, int $numRows = null)
    {
        // TODO: Implement delete() method.
    }

    function getOne(string $table, $columns = '*')
    {
        // TODO: Implement getOne() method.
    }

    function getValue(string $table, string $columns)
    {
        // TODO: Implement getValue() method.
    }

    function insertMulti(string $table, array $multiInsertData, array $dataKeys = null)
    {
        // TODO: Implement insertMulti() method.
    }

    function has(string $table)
    {
        // TODO: Implement has() method.
    }

    function where(string $whereProp, $whereValue = 'DBNULL', $operator = '=', $condition = 'AND')
    {
        if ( count($this->_where) === 0 ) {
            $condition = '';
        }
        $this->_where[] = array( $condition, $whereProp, $operator, $whereValue );
    }

    function orWhere(string $whereProp, $whereValue = 'DBNULL', $operator = '=')
    {
        $this->where($whereProp, $whereValue, $operator, 'OR');
    }

    function createTable()
    {
        // TODO: Implement createTable() method.
    }

    function addColumns()
    {
        // TODO: Implement addColumns() method.
    }

    function dropColumns()
    {
        // TODO: Implement dropColumns() method.
    }

    /**
     * @return mixed
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * @param mixed $prefix
     */
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
    }


    /**
     * @param string $fetchType
     */
    public function setFetchType(string $fetchType)
    {
        $this->fetchType = $fetchType;
    }

    public function arrayBuilder(): Database
    {
        $this->setFetchType('array');
        return $this;
    }

    public function jsonBuilder(): Database
    {
        $this->setFetchType('json');
        return $this;
    }

    public function objectBuilder(): Database
    {
        $this->setFetchType('object');
        return $this;
    }

    protected function _dynamicBindResults()
    {
        if ( $this->fetchType == 'array' ) {
            $results = $this->_stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $results = $this->_stmt->fetchAll(PDO::FETCH_OBJ);
        }
        if ( $this->fetchType == 'json' ) {
            $results = json_encode($results);
        }
        return $results;
    }

    protected function _buildQuery()
    {
        $this->_buildCondition('WHERE', $this->_where);
    }

    /**
     * @return PDOStatement
     */
    protected function _prepareQuery(): PDOStatement
    {
        $this->_buildQuery();
        $this->_stmt = $this->_connection->prepare($this->_query);
        $this->_bindParams();
        return $this->_stmt;
    }

    protected function _buildCondition($operator, &$condition)
    {
        $this->_query .= ' ' . $operator;
        foreach ( $condition as $cond ) {
            list($concat, $property, $op, $val) = $cond;
            $this->_query .= ' ' . $concat . ' ' . $property;
            switch ( strtolower($op) ) {
                case '':
                    break;
                default:
                    $this->_query .= ' ' . $op . ' ?';
                    $this->_params[] = $val;
            }

        }
    }

    protected function _bindParams(){
        foreach ($this->_params as $param_no=>$param_value){
            $this->_stmt->bindValue($param_no+1, $param_value);
        }
    }
}