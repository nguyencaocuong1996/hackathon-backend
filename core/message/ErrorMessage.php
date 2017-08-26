<?php
/**
 * Created by PhpStorm.
 * User: WINDNCC
 * Date: 4/2/2017
 * Time: 11:20 AM
 */

namespace wind\message;


class ErrorMessage extends Message
{
    function __construct()
    {
        $this->setMsgType('error');
    }
}