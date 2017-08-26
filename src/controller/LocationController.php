<?php
/**
 * Created by PhpStorm.
 * User: WINDNCC
 * Date: 8/26/2017
 * Time: 12:31 AM
 */

namespace fooco\controller;


use fooco\database\model\location\LocationCollection;
use fooco\location\LocationManagement;

class LocationController
{
    public function getAllLocation() : array {
        $lc = new LocationCollection('*');
        return $lc->buildTree();
    }
}