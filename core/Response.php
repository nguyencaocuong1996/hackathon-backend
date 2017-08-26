<?php

/**
 * Created by PhpStorm.
 * User: WINDNCC
 * Date: 4/13/2017
 * Time: 1:01 AM
 */

namespace wind;
class Response
{
    public $status = false;
    public $message = '';
    public $errors = array();
    public $data = array();

    function __construct(bool $status = false, string $message = '', array $data = array(), array $errors = array())
    {
        $this->setStatus($status);
        $this->setMessage($message);
        $this->setData($data);
        $this->setErrors($errors);
    }

    function toJson(bool $json_pretty_print = false): string
    {
        $res = array();
        $res[ 'status' ] = $this->status;
        if ($this->message !== '') $res[ 'message' ] = $this->message;
        if (isset($this->data) && ! empty($this->data)) $res[ 'data' ] = $this->data;
        if (isset($this->errors) && ! empty($this->errors)) $res[ 'errors' ] = $this->errors;
        if ($json_pretty_print) {
            return json_encode($res, JSON_PRETTY_PRINT);
        } else {
            return json_encode($res);
        }

    }

    function printJson(bool $json_pretty_print = false)
    {
        echo $this->toJson($json_pretty_print);
    }

    /**
     * @param bool $status
     */
    public function setStatus(bool $status)
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message)
    {
        $this->message = $message;
    }

    /**
     * @return bool
     */
    public function isStatus(): bool
    {
        return $this->status;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @param array $errors
     */
    public function setErrors(array $errors)
    {
        $this->errors = $errors;
    }
}