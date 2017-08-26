<?php
/**
 * Created by PhpStorm.
 * User: WINDNCC
 * Date: 4/5/2017
 * Time: 1:31 AM
 */

namespace wind\database;

use Exception;
use PDO;
use PDOException;

class MysqlDb extends Database
{

    /**
     * connect to mysql database
     * @ $_connection = new PDO;
     *
     * @throws Exception
     */
    function connect()
    {
        try {
            $portString = ! isset($this->port) ? '' : 'port=' . $this->port . ';';
            $this->_connectString = sprintf("mysql:host=%s;%sdbname=%s", $this->host, $portString, $this->db);
            $option = array( PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES ' . $this->charset );

            $this->_connection = new PDO($this->_connectString, $this->_username, $this->_password, $option);
            $this->_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch ( PDOException $exception ) {
            throw new Exception($exception->getMessage());
        }
    }


}