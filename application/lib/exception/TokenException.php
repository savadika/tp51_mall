<?php

/**
 * User:ylf
 * Time:2022/12/31/18:19
 * FileName:TokenException.php
 * Desc:'描述区'
 */


namespace app\lib\exception;

class TokenException extends UserException
{
    public function __construct($msg='Token异常错误', $httpCode = 400, $errorCode = 80000)
    {
        parent::__construct($msg, $httpCode, $errorCode);
    }
}