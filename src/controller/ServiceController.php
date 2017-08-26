<?php
/**
 * Created by PhpStorm.
 * User: WINDNCC
 * Date: 8/25/2017
 * Time: 3:39 PM
 */

namespace fooco\controller;


use fooco\database\DB;
use fooco\database\model\service\ServiceManagement;

class ServiceController
{
    private static $_instance;

    /**
     * @return mixed
     */
    public static function getInstance() : ServiceController
    {
        if (self::$_instance == null) self::$_instance = new ServiceController();
        return self::$_instance;
    }
    function getAllService(){
        
    }

    public function getById(int $id) : array {
        $service =  ServiceManagement::getInstance()->getOne(DB::COL_SERVICE_ID, $id);

        return $service;
    }
}