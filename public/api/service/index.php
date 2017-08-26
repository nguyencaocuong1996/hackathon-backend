<?php
/**
 * Created by PhpStorm.
 * User: WINDNCC
 * Date: 8/25/2017
 * Time: 10:17 PM
 */
use wind\Response;
use fooco\controller\ServiceController;
require_once '../../../load.php';
header("Content-type: Application/Json");
if (isset($_REQUEST['action'])){
    $serviceController = ServiceController::getInstance();
    $action = $_REQUEST['action'];
    $res = new Response();
    switch ($action){
        case 'create' :
            $service = $serviceController->createService($_REQUEST);
            $res->setMessage("create service success");
            $res->setStatus(true);
            $res->setData($service);
            break;
        case 'get' :
            $typeId = $_REQUEST['serviceTypeId'];
            $listService = $serviceController->getByTypeId($typeId);
            $res->setData($listService);
            $res->setStatus(true);
            $res->setMessage("Get success!");
            break;
        case 'default':
            die("Bad request!");
            break;
    }
    echo $res->toJson(true);
} else {
    die("Bad request!");
}