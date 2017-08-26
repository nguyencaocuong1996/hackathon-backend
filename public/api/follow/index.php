<?php
/**
 * Created by PhpStorm.
 * User: WINDNCC
 * Date: 8/25/2017
 * Time: 10:17 PM
 */
use wind\Response;
use fooco\controller\FollowController;
use fooco\database\DB;
require_once '../../../load.php';
header("Content-type: Application/Json");
if (isset($_REQUEST['action'])){
    $followController = FollowController::getInstance();
    $action = $_REQUEST['action'];
    $res = new Response();
    switch ($action){
        case 'getByUserId' :
            $props = array(
                DB::COL_USER_ID=>'',
                DB::COL_USER_NAME=>DB::TABLE_USER
            );
            $userId = $_REQUEST[DB::COL_USER_ID];
            $listFollowed = $followController->getFollowByUserId($userId, $props);
            $res->setMessage("Get follow success");
            $res->setStatus(true);
            $res->setData($listFollowed);
            break;

        case 'follow' :
            $userId = $_REQUEST[DB::COL_USER_ID];
            $followId = $_REQUEST[DB::COL_USER_FOLLOW_ID];
            if ($followController->follow($_REQUEST) == true){
                $res->setMessage("Follow success!");
                $res->setStatus(true);
            };
            break;

        case 'default':
            die("Bad request!");
            break;
    }
    echo $res->toJson(true);
}