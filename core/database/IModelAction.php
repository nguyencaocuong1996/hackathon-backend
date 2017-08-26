<?php
/**
 * Created by PhpStorm.
 * User: WINDNCC
 * Date: 4/5/2017
 * Time: 3:12 PM
 */

namespace wind\database;


interface IModelAction
{
    function getOne($getProperty, $value);

    function save(&$record): Record;

    function delete($record): bool;

}