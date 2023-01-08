<?php

/**
 * User:ylf
 * Time:2023/1/8/17:03
 * FileName:SuccessMsg.php
 * Desc:'描述区'
 */


namespace app\lib\exception;



class SuccessMsg extends UserException
{
    public function __construct($msg='OK', $httpCode = 201, $errorCode = 0)
    {
        parent::__construct($msg, $httpCode, $errorCode);
    }
}