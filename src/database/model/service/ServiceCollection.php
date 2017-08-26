<?php

/**
 * Created by PhpStorm.
 * User: WINDNCC
 * Date: 4/11/2017
 * Time: 10:32 AM
 */

namespace fooco\database\model\service;

use wind\database\RecordCollection;
use fooco\database\DB;
use wind\Time;

/**
 * @property array|bool|string _properties
 */
class ServiceCollection extends RecordCollection
{
    private $listAvatar;
    protected static $_table;
    function __construct($properties)
    {
        self::$_table = DB::TABLE_SERVICE;
        $this->checkGetProperties($properties);
        $this->_properties = DB::buildGetProperties($properties, self::$_table);
        $this->listAvatar = array(
            "http://www.elleoncito.com/admin/images/default_slider.jpg",
            "http://www.ozarlington.com/wp-content/uploads/2017/04/bar-buffet.jpg",
            "https://media-cdn.tripadvisor.com/media/photo-s/06/ac/70/5e/fardi-syrian-restaurant.jpg"
        );
    }

    function getByType($typeId = 0) : ServiceCollection
    {
        if ($typeId!=0){
            global $db;
            $db->where(DB::COL_SERVICE_TYPE_ID, $typeId);
            $this->_collection = $db->get(self::$_table, null, $this->_properties);
            array_walk($this->_collection, function(&$item, $key){
                $randIndex = array_rand($this->listAvatar);
                $item[DB::COL_SERVICE_AVATAR] = $this->listAvatar[$randIndex];
            });
        }
        return $this;
    }

    function checkGetProperties(&$properties)
    {
        // TODO: Implement checkGetProperties() method.
    }
}