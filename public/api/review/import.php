<?php
/**
 * Created by PhpStorm.
 * User: WINDNCC
 * Date: 8/26/2017
 * Time: 11:17 PM
 */

require_once '../../../load.php';
use fooco\database\DB;
$listService = \fooco\controller\ServiceController::getInstance()->getByTypeId(1);
$listUser =     \fooco\controller\UserController::getInstance()->getAllUserId();
$listUserId = array();
$listServiceId = array();
array_map(function($user){
    global $listUserId;
    $listUserId[] = $user['userId'];
}, $listUser);
array_map(function($value){
    global $listServiceId;
    $listServiceId[] = $value['serviceId'];
},$listService);
var_dump($listServiceId);
var_dump($listUserId);
foreach ($listServiceId as $serviceId){
    foreach ($listUserId as $userId){
        $content = "Đây là review của user " . $userId . " cho service " . $serviceId;
        $data = array();
        $data[DB::COL_SERVICE_ID] = $serviceId;
        $data[DB::COL_USER_ID] = $userId;
        $data[DB::COL_REVIEW_STAR_FOR_SERVICE] = rand(2,5);
        $data[DB::COL_REVIEW_CONTENT] = $content;
        \fooco\controller\ReviewController::getInstance()->reviewService($data);
    }
}