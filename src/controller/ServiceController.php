<?php
/**
 * Created by PhpStorm.
 * User: WINDNCC
 * Date: 8/25/2017
 * Time: 3:39 PM
 */

namespace fooco\controller;

use fooco\database\model\service\Service;
use fooco\database\model\service\ServiceCollection;
use fooco\database\model\service\ServiceManagement;

class ServiceController
{
    private static $_instance;
    private $serviceManagement;

    /**
     * @return mixed
     */
    public static function getInstance() : ServiceController
    {
        if (self::$_instance == null) self::$_instance = new ServiceController();
        return self::$_instance;
    }

    function __construct()
    {
        $this->serviceManagement = ServiceManagement::getInstance();
    }

    function getAllService(){
        
    }

    function getByTypeId($typeId) : array
    {
        $serviceCollection = new ServiceCollection('*');
        $serviceCollection->getByType($typeId);
        return $serviceCollection->toArray();
    }

    public function getById(int $id) : array {

        return array();
    }

    public function createService($params) : array
    {
        $newService = new Service($params);
        $this->serviceManagement->save($newService);
        return $newService->toArray();
    }
}