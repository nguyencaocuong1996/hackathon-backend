<?php
/**
 * Created by PhpStorm.
 * User: WINDNCC
 * Date: 8/25/2017
 * Time: 10:17 PM
 */
use wind\Response;
use fooco\controller\OwnerController;
require_once '../../../load.php';
header("Content-type: Application/Json");
if (isset($_REQUEST['a']) && isset($_REQUEST['params'])){
    $ownerController = OwnerController::getInstance();
    $action = $_REQUEST['a'];
    $res = new Response();
    $ownerData = json_decode($_REQUEST['params'], true);
    switch ($action){
        case 'create' :
            $owner = $ownerController->createOwner($ownerData);
            $res->setMessage("create owner success");
            $res->setStatus(true);
            $res->setData($owner);
            break;
        case 'default':
            die("Bad request!");
            break;
    }
    echo $res->toJson(true);
}