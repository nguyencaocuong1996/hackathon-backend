<?php
/**
 * Created by PhpStorm.
 * User: WINDNCC
 * Date: 4/2/2017
 * Time: 11:19 AM
 */

namespace wind\message;

class Message
{
    public $msgId;
    public $msgContent;
    public $msgType;
    public $msgFor;
    public $active = false;
    /**
     * @param mixed $msgId
     */
    public function setMsgId($msgId)
    {
        $this->msgId = $msgId;
    }

    /**
     * @param mixed $msgContent
     */
    public function setMsgContent($msgContent)
    {
        $this->msgContent = $msgContent;
    }

    /**
     * @param mixed $msgFor
     */
    public function setMsgFor($msgFor)
    {
        $this->msgFor = $msgFor;
    }

    /**
     * @param mixed $msgType
     */
    public function setMsgType($msgType)
    {
        $this->msgType = $msgType;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }
    public function active(){
        $this->active = true;
    }
}