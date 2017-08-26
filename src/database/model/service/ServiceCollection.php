<?php

/**
 * Created by PhpStorm.
 * User: WINDNCC
 * Date: 4/11/2017
 * Time: 10:32 AM
 */

namespace football\pitchs;

use wind\database\RecordCollection;
use fooco\database\DB;
use wind\Time;

class ServiceCollection extends RecordCollection
{
    protected static $_table;
    private $_getPitchAvatar = false;
    private $_getPitchLocation = false;
    private $_getPitchPrice = false;
    private $_getPitchTimeSlot = false;
    function __construct($properties)
    {
        self::$_table = DB::TABLE_SERVICE;
        $this->checkGetProperties($properties);
        $this->_properties = DB::buildGetProperties($properties, self::$_table);
    }

    function getAll() : ServiceCollection
    {
        global $db;
        $db->where(DB::TABLE_PITCH . '.' . DB::COLUMN_PITCH_PARENT, null, "IS");
        $this->_collection = $db->get(self::$_table, null, $this->_properties);
        $this->_getPitchAvatar();
        $this->_getPitchLocation();
        $this->_getPitchTimeSlot();
        return $this;
    }

    function getByOwnerId($ownerId): ServiceCollection
    {
        global $db;
        $db->where(DB::TABLE_PITCH . '.' . DB::COLUMN_OWNER_ID, $ownerId);
        $this->_collection = $db->get(self::$_table, null, $this->_properties);
        $this->_getPitchAvatar();
        $this->_getPitchLocation();
        $this->_getPitchTimeSlot();
        return $this;
    }

    function getByLocation(Location $location, $just_free_pitch = false) : ServiceCollection
    {
        global $db;
        if ($just_free_pitch == false){
            $joinCond = DB::TABLE_PITCH.'.'.DB::COLUMN_PITCH_ID.' = '.DB::TABLE_PITCH_LOCATION.'.'.DB::COLUMN_PITCH_ID;
            $db->join(DB::TABLE_PITCH_LOCATION, $joinCond);
            $db->where(DB::TABLE_PITCH_LOCATION.'.'.DB::COLUMN_LOCATION_ID, $location->{DB::COLUMN_LOCATION_ID});
            $this->_collection = $db->get(DB::TABLE_PITCH,null, $this->_properties);
        } else {
            $query = "SELECT p1.pitchId, p1.pitchName, p1.pitchPhone
                        FROM pitchs as p1 
                        LEFT JOIN pitch_location as pl ON pl.pitchId = p1.pitchId
                      WHERE 
	                    (SELECT COUNT(pitchTimeSlotId) FROM `pitch_time_slot` as pts WHERE `pitchTimeSlotId` NOT IN(SELECT pitchTimeSlotId FROM pitch_orders WHERE orderDate=?) 
	                    AND pts.pitchId = p1.pitchId) > 0 AND pl.locationId = ? ";
            $this->_collection = $db->rawQuery($query,array(date("Y-m-d"), $location->{DB::COLUMN_LOCATION_ID}));
//            echo $db->getLastQuery();
        }

        $this->_getPitchAvatar();
        $this->_getPitchLocation();
        $this->_getPitchTimeSlot();
        return $this;
    }
    function getByNameAndLocation(Location $location, string $name = null, bool $just_free_pitch = false) : ServiceCollection
    {
        if ($name == null){
            return $this->getByLocation($location, $just_free_pitch);
        } else {
            global $db;
            if ($just_free_pitch == false){
                $joinCond = DB::TABLE_PITCH.'.'.DB::COLUMN_PITCH_ID.' = '.DB::TABLE_PITCH_LOCATION.'.'.DB::COLUMN_PITCH_ID;
                $db->join(DB::TABLE_PITCH_LOCATION, $joinCond);
                $db->where(DB::TABLE_PITCH_LOCATION.'.'.DB::COLUMN_LOCATION_ID, $location->{DB::COLUMN_LOCATION_ID});
                $db->where(DB::COLUMN_PITCH_NAME, '%'.$name.'%', "LIKE");
                $this->_collection = $db->get(DB::TABLE_PITCH,null, $this->_properties);
            } else {
                $query = "SELECT p1.pitchId, p1.pitchName, p1.pitchPhone
                        FROM pitchs as p1 
                        LEFT JOIN pitch_location as pl ON pl.pitchId = p1.pitchId
                      WHERE 
	                    (SELECT COUNT(pitchTimeSlotId) FROM `pitch_time_slot` as pts WHERE `pitchTimeSlotId` NOT IN(SELECT pitchTimeSlotId FROM pitch_orders WHERE orderDate=?) 
	                    AND pts.pitchId = p1.pitchId) > 0 AND pl.locationId = ? AND p1.pitchName LIKE '%".$name."%'";
                $this->_collection = $db->rawQuery($query,array(date("Y-m-d"), $location->{DB::COLUMN_LOCATION_ID}));
//                echo $db->getLastQuery();
            }

            $this->_getPitchAvatar();
            $this->_getPitchLocation();
            $this->_getPitchTimeSlot();
            return $this;
        }
    }
    function checkGetProperties(&$properties)
    {
        if (is_array($properties)) {
            if (array_key_exists(DB::PROP_PITCH_AVATAR, $properties)) {
                $this->_getPitchAvatar = true;
                unset($properties[ DB::PROP_PITCH_AVATAR ]);
            }
            if (array_key_exists(DB::PROP_PITCH_LOCATION, $properties)) {
                $this->_getPitchLocation = true;
                unset($properties[ DB::PROP_PITCH_LOCATION ]);
            }
            if (array_key_exists(DB::PROP_PITCH_PRICE, $properties)) {
                $this->_getPitchPrice = true;
                unset($properties[ DB::PROP_PITCH_PRICE ]);
            }
            if (array_key_exists("timeSlot", $properties)){
                $this->_getPitchTimeSlot = true;
                unset($properties["timeSlot"]);
            }
        }
    }

    function buildTree(int $parent = null, array $a = array(), string $idColumn = DB::COLUMN_PITCH_ID, string $parentColumn = DB::COLUMN_PITCH_PARENT): array
    {
        return parent::buildTree($parent, $this->toArray(), $idColumn, $parentColumn);
    }

    /**
     * @return bool
     */
    public function isGetPitchAvatar(): bool
    {
        return $this->_getPitchAvatar;
    }

    /**
     * @return bool
     */
    public function isGetPitchLocation(): bool
    {
        return $this->_getPitchLocation;
    }

    /**
     * @return bool
     */
    public function isGetPitchPrice(): bool
    {
        return $this->_getPitchPrice;
    }

    /**
     * @return bool
     */
    public function isGetPitchTimeSlot(): bool
    {
        return $this->_getPitchTimeSlot;
    }

    private function _getPitchAvatar()
    {
        if ($this->isGetPitchAvatar()) {
            global $db;
            foreach ($this->_collection as $key => $pitch) {
                $db->where(DB::COLUMN_PITCH_ID, $pitch[ DB::COLUMN_PITCH_ID ]);
                $db->where(DB::COLUMN_PITCH_MEDIA_FOR, MediaFor::PITCH_AVATAR);
                $mediaId = $db->getValue(DB::TABLE_PITCH_MEDIA, DB::COLUMN_MEDIA_ID);
                if ($mediaId === null) {
                    $pitch[ DB::PROP_PITCH_AVATAR ] = null;
                } else {
                    $avatar = MediaManagement::getInstance()->getById($mediaId)->getUrl();
                    $pitch[ DB::PROP_PITCH_AVATAR ] = $avatar;
                }
                $this->_collection[ $key ] = $pitch;
            }
        }
    }

    private function _getPitchLocation()
    {
        if ($this->isGetPitchLocation()) {
            global $db;
            foreach ($this->_collection as $key => $pitch) {
                $db->where(DB::COLUMN_PITCH_ID, $pitch[ DB::COLUMN_PITCH_ID ]);
                $pitch_location = $db->getOne(DB::TABLE_PITCH_LOCATION,[DB::COLUMN_LOCATION_ID, DB::COLUMN_PITCH_LOCATION_ADDRESS, DB::COLUMN_LOCATION_LAT, DB::COLUMN_LOCATION_LNG]);
                if (isset($pitch_location)){
                    $location = LocationManagement::getInstance()->getById($pitch_location[DB::COLUMN_LOCATION_ID]);
                    $pitch[DB::PROP_PITCH_LOCATION] = [
                        "address"=>$pitch_location[DB::COLUMN_PITCH_LOCATION_ADDRESS].', '.$location,
                        "geoLocation"=>[
                            "lat"=>$pitch_location[DB::COLUMN_LOCATION_LAT],
                            "lang"=>$pitch_location[DB::COLUMN_LOCATION_LNG]
                            ]
                        ];
                    $this->_collection[$key] = $pitch;
                } else {
                    continue;
                }
            }
        }
    }
    private function _getPitchTimeSlot(){
        if ($this->isGetPitchTimeSlot()){
            foreach ($this->_collection as $key=>$pitch){
                $price_collection = new PitchTimeSlotCollection($pitch[ DB::COLUMN_PITCH_ID ]);
                $price_collection = $price_collection->get();
                $pitch["timeSlot"] = $price_collection->toArray();
                $this->_collection[$key] = $pitch;
            }
        }
    }
    private function _getPitchPrice()
    {
        if ($this->isGetPitchPrice()) {
            global $db;
            foreach ($this->_collection as $key => $pitch) {
                $db->where(DB::COLUMN_PITCH_ID, $pitch[ DB::COLUMN_PITCH_ID ]);
                $pitch_price = $db->get(DB::TALBE_PITCH_PRICE,null ,[DB::COLUMN_PITCH_PRICE_FROM_TIME, DB::COLUMN_PITCH_PRICE_TO_TIME, DB::COLUMN_PITCH_PRICE_PRICE]);
                if (isset($pitch_price)){
                    $pitch[DB::PROP_PITCH_PRICE] = null;
                    foreach ($pitch_price as $price){
                        $fromTime = new Time($price[DB::COLUMN_PITCH_PRICE_FROM_TIME]);
                        $toTime = new Time($price[DB::COLUMN_PITCH_PRICE_TO_TIME]);
                        $pitch[DB::PROP_PITCH_PRICE] .= ((isset($pitch[DB::PROP_PITCH_PRICE])) ? ',':'') . $fromTime->getHour().'-'.$toTime->getHour().':'.$price[DB::COLUMN_PITCH_PRICE_PRICE];
                    }
                    $this->_collection[$key] = $pitch;
                } else {
                    continue;
                }
            }
        }
    }
}