<?php
/**
 * Created by PhpStorm.
 * User: WINDNCC
 * Date: 5/3/2017
 * Time: 1:01 AM
 */

namespace wind;
class Time
{
    public $hour;
    public $minute;
    public $second;
    function __construct($h, $m = null, $s = null)
    {
        if (is_string($h) && $m === null && $s === null){
            $time = explode(':',$h);
            if ($time[0] > 24 || $time[1] > 60 || $time[2] > 60){
                return;
            } else {
                $this->hour = (int)$time[0];
                $this->minute = (int)$time[1];
                $this->second = (int)$time[2];
            }

        } else {
            $this->hour = $h;
            $this->minute = $m;
            $this->second = $s;
        }
    }

    public static function compare(Time $time1, Time $time2){
        $res = 0;
        if ($time1->hour < $time2->hour){
            $res = -1;
        } elseif($time1->hour > $time2->hour){
            $res = 1;
        } else {
            if ($time1->minute < $time2->minute){
                $res = -1;
            } elseif($time1->minute > $time2->minute){
                $res = 1;
            } else {
                if ($time1->second < $time2->second){
                    $res = -1;
                } elseif($time1->second > $time2->second){
                    $res = 1;
                } else {
                    $res = 0;
                }
            }
        }
        return $res;
    }
    function __toString()
    {
        $h = $this->hour < 10 ? '0'.$this->hour : $this->hour;
        $m = $this->minute < 10 ? '0'.$this->minute : $this->minute;
        $s = $this->second < 10 ? '0'.$this->second : $this->second;
        return $h.':'.$m.':'.$s;
    }

    public function set($time)
    {
        if ($time instanceof Time){
            $this->hour = $time->getHour();
            $this->minute = $time->getMinute();
            $this->second = $time->getSecond();
        } elseif(is_string($time)) {
            $time = explode(':',$time);
            if ($time[0] > 24 || $time[1] > 60 || $time[2] > 60){
                return;
            } else {
                $this->hour = $time[0];
                $this->minute = $time[1];
                $this->second = $time[2];
            }
        }
    }

    public function addHour(int $hour)
    {
        $this->hour += $hour;
    }
    public function addMinute(int $minute) : Time
    {
        $this->addHour(($minute+$this->getMinute()) / 60);
        $this->minute = ($minute + $this->getMinute()) % 60;
        return $this;
    }
    /**
     * @return mixed
     */
    public function getHour()
    {
        return $this->hour;
    }

    /**
     * @param mixed $hour
     */
    public function setHour($hour)
    {
        $this->hour = $hour;
    }

    /**
     * @return mixed
     */
    public function getMinute()
    {
        return $this->minute;
    }

    /**
     * @param mixed $minute
     */
    public function setMinute($minute)
    {
        $this->minute = $minute;
    }

    /**
     * @return mixed
     */
    public function getSecond()
    {
        return $this->second;
    }

    /**
     * @param mixed $second
     */
    public function setSecond($second)
    {
        $this->second = $second;
    }

}