<?php
/**
 * Created by PhpStorm.
 * User: WINDNCC
 * Date: 8/25/2017
 * Time: 3:51 PM
 */

require_once ('load.php');

$a = array(
    \fooco\database\DB::COL_LOCATION_ID => '',
    \fooco\database\DB::COL_LOCATION_NAME => '',
    \fooco\database\DB::COL_LOCATION_PARENT => ''
);
$lc = new \fooco\database\model\location\LocationCollection('*');
var_dump($lc->buildTree());
echo "ASdsadsaddas";