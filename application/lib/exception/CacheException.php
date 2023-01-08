<?php

/**
 * User:ylf
 * Time:2022/12/31/13:38
 * FileName:CacheException.php
 * Desc:'描述区'
 */


namespace app\lib\exception;

class CacheException extends UserException
{
    public function __construct($msg='缓存错误！', $httpCode = 400, $errorCode = 70000)
    {
        parent::__construct($msg, $httpCode, $errorCode);
    }
}