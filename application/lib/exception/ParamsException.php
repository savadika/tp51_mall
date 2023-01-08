<?php

/**
 * User:ylf
 * Time:2022/10/25/16:48
 * FileName:ParamsException.php
 * Desc:'描述区'
 */


namespace app\lib\exception;

use app\lib\exception\UserException;

class ParamsException  extends UserException
{
    public function __construct($msg='参数异常错误', $httpCode = 401, $errorCode = 10002)
    {
        parent::__construct($msg, $httpCode, $errorCode);
    }
}