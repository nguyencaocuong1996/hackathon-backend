<?php
/**
 * Created by PhpStorm.
 * User: WINDNCC
 * Date: 8/26/2017
 * Time: 6:24 PM
 */

namespace fooco\controller;


use fooco\database\DB;
use fooco\database\model\review\ReviewCollection;

class ReviewController
{
    private static $_instance;
    public function __construct()
    {

    }

    /**
     * @return ReviewController
     */
    public static function getInstance(): ReviewController
    {
        if (self::$_instance == null) self::$_instance = new ReviewController();
        return self::$_instance;
    }
//    private static reviewMa
    function getByServiceId($serviceId, $props) : array
    {
        $reviewCollection = new ReviewCollection('*');
        $reviewCollection->setProperties($props);
        return $reviewCollection->getByServiceId($serviceId)->toArray();
    }

    function reviewService($reviewData) : bool
    {
//        var_dump($reviewData);
        $serviceId = $reviewData[DB::COL_SERVICE_ID];
        $starForService = $reviewData[DB::COL_REVIEW_STAR_FOR_SERVICE];
        $reviewContent = $reviewData[DB::COL_REVIEW_CONTENT];
        $userId = $reviewData[DB::COL_USER_ID];
        $dataIns = array(
            DB::COL_REVIEW_CONTENT=>$reviewContent,
            DB::COL_USER_ID=>$userId,
            DB::COL_SERVICE_ID=>$serviceId,
            DB::COL_REVIEW_STAR_FOR_SERVICE=>$starForService
        );
//        var_dump($dataIns);
        global $db;
        if ($db->insert(DB::TABLE_REVIEW, $dataIns)){
                $db->where(DB::COL_SERVICE_ID, $serviceId);
                $dataUpd = array(
                    DB::COL_SERVICE_REVIEW_STAR=>$db->inc($starForService),
                    DB::COL_SERVICE_REVIEW_NUM=>$db->inc(1)
                );
                 $db->update(DB::TABLE_SERVICE, $dataUpd, 1);
                return true;
        }
        return false;
    }

    function reviewComment($reviewData) : bool
    {
        global $db;
        $commentId = $reviewData[DB::COL_REVIEW_ID];
        $numStar = $reviewData[DB::COL_REVIEW_STAR];
        $userOfComment = $reviewData[DB::COL_USER_ID];
        $dataUpd = array(
            DB::COL_REVIEW_STAR=>$db->inc($numStar),
            DB::COL_REVIEW_NUM=>$db->inc(1)
        );
        $db->where(DB::COL_REVIEW_ID, $commentId);
        if ($db->update(DB::TABLE_REVIEW, $dataUpd)){
            $db->where(DB::COL_USER_ID, $userOfComment);
            $dataUpdUser = array(
                DB::COL_USER_TOTAL_STAR=>$db->inc($numStar),
                DB::COL_USER_TOTAL_REVIEW=>$db->inc(1)
            );
            return ($db->update(DB::TABLE_USER, $dataUpdUser));
        };
        return false;
    }
}