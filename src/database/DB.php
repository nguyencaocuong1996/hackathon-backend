<?php

/**
 * Created by PhpStorm.
 * User: WINDNCC
 * Date: 4/6/2017
 * Time: 1:14 AM
 */

namespace fooco\database;
abstract class DB
{

    //USER
    const TABLE_USER = 'users';
    const COL_USER_ID = 'userId';
    const COL_USER_NAME = 'userName';
    const COL_USER_PASSWORD = 'userPassword';
    const COL_USER_PHONE = 'userPhone';

    //OWNER
    const TABLE_OWNER = 'owners';
    const COL_OWNER_ID = 'ownerId';
    const COL_OWNER_USER_NAME = 'ownerUsername';
    const COL_OWNER_PASSWORD = 'ownerPassword';
    const COL_OWNER_PHONE = 'ownerPhone';
    const COL_OWNER_EMAIL = 'ownerEmail';
    const COL_OWNER_JOIN_DATE = 'userJoinDate';

    //SERVICE
    const TABLE_SERVICE = 'services';
    const COL_SERVICE_ID = 'serviceId';
    const COL_SERVICE_NAME = 'serviceName';

    //SERVICE TYPE
    const TABLE_SERVICE_TYPE = 'service_type';
    const COL_SERVICE_TYPE_ID = 'serviceTypeId';
    const COL_SERVICE_TYPE_NAME = 'serviceTypeName';

    //REVIEW
    const TABLE_REVIEW = 'reviews';
    const COL_REVIEW_ID = 'reviewId';
    const COL_REVIEW_USER_NAME = 'reviewUserName';
    const COL_REVIEW_CONTENT = 'reviewContent';

    //OPTIONAL CONST

    //TABLE BOOK
    const TABLE_BOOK = 'book';
    const COL_BOOK_ID = 'bookId';
    const COL_BOOK_DATE_TIME = 'bookDateTime';
    const COL_BOOK_MESSAGE = 'bookMessage';

    //LOCATION
    const TABLE_LOCATION = 'locations';
    const COL_LOCATION_ID = 'locationId';
    const COL_LOCATION_NAME = 'locationName';
    const COL_LOCATION_PARENT = 'locationParent';

    //ADDRESS
    const TABLE_ADDRESS = 'address';
    const COL_ADDRESS_ID = 'addressId';
    const COL_ADDRESS_DETAIL = 'addressDetail';
    const COL_LOCATION_LAT = 'addressLat';
    const COL_LOCATION_LNG = 'addressLng';

    static function buildGetProperties($properties, $defaultTable = null)
    {
        if (! is_array($properties)) {
            if ($properties === null) {
                $properties = '*';
                return $properties;
            } else {
                if (is_string($properties)) {
                    $properties = explode(',', $properties);
                    $properties = array_flip($properties);
                    foreach ($properties as $property => $val) {
                        $properties[ $property ] = '';
                    }
                }
            }

        }
        if ($defaultTable != null) {
            foreach ($properties as $key => $value) {
                if ($value == '' || $value == null) {
                    $properties[ $key ] = $defaultTable;
                }
            }
        }
        $properties = isset($properties) ? $properties : '*';
        if (is_array($properties)) {
            $properties = array_map(function ($key, $val) {
                if ($val != null) {
                    return $val . '.' . $key;
                } else {
                    return $key;
                }
            }, array_keys($properties), $properties);
        }
        return $properties;
    }

    static function buildJoinTableStruct($joinTable, $joinOn, $tableFields, $joinWithTable = null)
    {
        if ($joinWithTable != null) $joinOn = $joinWithTable . '.' . $joinOn;
        return array( $joinTable => array_merge(array( 'on' => $joinOn ), $tableFields) );
    }
}