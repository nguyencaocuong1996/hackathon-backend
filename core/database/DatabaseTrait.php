<?php
/**
 * Created by PhpStorm.
 * User: WINDNCC
 * Date: 4/6/2017
 * Time: 12:49 AM
 */

namespace wind\database;

trait DatabaseTrait
{
    private $database;

    /**
     * @return mixed
     */
    /**
     * @param mixed $database
     */
    public function getDatabase()
    {
        if (!isset($this->database)) {
            $this->database = MysqliDb::getInstance();
        }
        return $this->database;
    }
}