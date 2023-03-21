<?php

/**
 * User:ylf
 * Time:2023/3/3/08:58
 * FileName:OrderException.php
 * Desc:'描述区'
 */


namespace app\lib\exception;

class OrderException extends UserException
{
    public function __construct($msg='订单编号异常', $httpCode = 400, $errorCode = 90000)
    {
        parent::__construct($msg, $httpCode, $errorCode);
    }

}