<?php
/**
 * Created by PhpStorm.
 * User: WINDNCC
 * Date: 8/25/2017
 * Time: 10:17 PM
 */

use wind\Response;
use fooco\controller\ReviewController;
use fooco\database\DB;
require_once '../../../load.php';
header("Content-type: Application/Json");

if (isset($_REQUEST['action'])){
    $reviewController = ReviewController::getInstance();
    $action = $_REQUEST['action'];
    $res = new Response();
    switch ($action){
        case 'getByServiceId' :
            $serviceId = $_REQUEST['serviceId'];
            $props = array(
                DB::COL_USER_NAME=>DB::TABLE_USER,
                DB::COL_REVIEW_STAR_FOR_SERVICE=>'',
                DB::COL_REVIEW_STAR=>'',
                DB::COL_REVIEW_CONTENT=>'',
                DB::COL_REVIEW_NUM=>''
            );
            $listReview = $reviewController->getByServiceId($serviceId, $props);
            $res->setData($listReview);
            $res->setStatus(true);
            $res->setMessage("Get success!");
            break;

        case 'reviewService' :
            if ($reviewController->reviewService($_REQUEST)){
                $res->setMessage("review service success!");
                $res->setStatus(true);
            } else {
                $res->setMessage("review service fail!");
            };
            break;


        case 'reviewComment' :
            if ($reviewController->reviewComment($_REQUEST)){
                $res->setMessage("review comment success!");
                $res->setStatus(true);
            } else {
                $res->setMessage("review comment fail!");
            }
            break;

        case 'default':
            die("Bad request!");
            break;
    }
    echo $res->toJson(true);
} else {
    die("Bad request!");
}