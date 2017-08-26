<?php
/**
 * Created by PhpStorm.
 * User: WINDNCC
 * Date: 4/5/2017
 * Time: 9:39 AM
 */

namespace wind\database;
use PDO;
use PDOException;

class SqlSeverDb extends Database
{

    function connect()
    {
        try {
            $conn = new PDO("sqlsrv:Server = tcp:nccdb.database.windows.net,1433; Database = dltc", "windncc", "db123456!");
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch (PDOException $e) {
            print("Error connecting to SQL Server.");
            die(print_r($e));
        }
    }
}