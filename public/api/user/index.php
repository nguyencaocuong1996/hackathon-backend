<?php
/**
 * Created by PhpStorm.
 * User: WINDNCC
 * Date: 8/25/2017
 * Time: 10:17 PM
 */
use wind\Response;
use fooco\controller\UserController;
require_once '../../../load.php';
header("Content-type: Application/Json");
if (isset($_REQUEST['action'])){
    $userController = UserController::getInstance();
    $res = new Response();
    $action = $_REQUEST['action'];
    switch ($action){
        case 'create' :
            if (!empty($_REQUEST)){
                $user = $userController->createUser($_REQUEST);
                if (!empty($user)) {
                    $res->setMessage("create owner success");
                    $res->setStatus(true);
                    $res->setData($user);
                } else {
                    $res->setMessage("user can't create!");
                }

            } else {
                $res->setMessage("params fail");
            }
            break;
        case 'login' :
            $user = $userController->login($_REQUEST);
            if (empty($user)) {
                $res->setMessage("Login fail!");
            } else {
                $res->setData($user);
                $res->setStatus(true);
                $res->setMessage("Login success!");
            }
            break;
        case 'default':
            die("Bad request!");
            break;
    }
    echo $res->toJson(true);
}