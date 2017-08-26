<?php
/**
 * Created by PhpStorm.
 * User: WINDNCC
 * Date: 4/5/2017
 * Time: 1:42 AM
 */

namespace wind\database;


interface IDatabaseAction
{
    function createTable();
    function addColumns();
    function dropColumns();
}