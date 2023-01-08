<?php

/**
 * User:ylf
 * Time:2022/10/23/17:46
 * FileName:BannerMissException.php
 * Desc:'描述区'
 */


namespace app\lib\exception;
use app\lib\exception\UserException;

class BannerMissException extends UserException
{
    public function __construct($msg='banner丢了', $httpCode = 401, $errorCode = 10001)
    {
        parent::__construct($msg, $httpCode, $errorCode);
    }
}