<?php
/**
 * Created by PhpStorm.
 * User: WINDNCC
 * Date: 4/2/2017
 * Time: 10:13 AM
 */

namespace wind\database;


interface ITableAction
{
    function insert(string $table, array $insertData);

    function update(string $table, array $tableData, int $numRows = null);

    function get(string $table, int $numRows = null, $columns = '*');

    function delete(string $table, int $numRows = null);

    function getOne(string $table, $columns = '*');

    function getValue(string $table, string $columns);

    function insertMulti(string $table, array $multiInsertData, array $dataKeys = null);

    function has(string $table);

    function where(string $whereProp, $whereValue = 'DBNULL', $operator = '=', $condition = 'AND');

    function orWhere(string $whereProp, $whereValue = 'DBNULL', $operator = '=');
}