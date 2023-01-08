<?php

/**
 * User:ylf
 * Time:2022/12/18/19:55
 * FileName:ProductException.php
 * Desc:'描述区'
 */


namespace app\lib\exception;

class ProductException extends UserException
{
    public function __construct($msg='商品信息异常', $httpCode = 400, $errorCode = 70000)
    {
        parent::__construct($msg, $httpCode, $errorCode);
    }
}