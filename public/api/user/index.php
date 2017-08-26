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
//header("Content-type: Application/Json");
if (isset($_REQUEST['a']) && isset($_REQUEST['params'])){
    $userController = UserController::getInstance();
    $action = $_REQUEST['a'];
    $res = new Response();
    $params = json_decode($_REQUEST['params'], true);
    switch ($action){
        case 'create' :
            if (!empty($params)){
                $user = $userController->createUser($params);
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
            $user = $userController->login($params);
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