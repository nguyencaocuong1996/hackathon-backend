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
if (isset($_REQUEST['action'])){
    $ownerController = OwnerController::getInstance();
    $action = $_REQUEST['action'];
    $res = new Response();
    switch ($action){
        case 'create' :
            $owner = $ownerController->createOwner($_REQUEST);
            $res->setMessage("create owner success");
            $res->setStatus(true);
            $res->setData($owner);
            break;
        case 'login' :
            $owner = $ownerController->login($_REQUEST);
            if (!empty($owner)){
                $res->setData($owner);
                $res->setStatus(true);
                $res->setMessage("login success!");
            } else {
                $res->setMessage("login fail!");
            }
            break;
        case 'default':
            die("Bad request!");
            break;
    }
    echo $res->toJson(true);
}