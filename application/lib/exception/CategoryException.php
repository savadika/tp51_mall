<?php

/**
 * User:ylf
 * Time:2022/12/18/15:04
 * FileName:CategoryException.php
 * Desc:'描述区'
 */


namespace app\lib\exception;

class CategoryException extends UserException
{
    public function __construct($msg='分类错误', $httpCode = 400, $errorCode = 60000)
    {
        parent::__construct($msg, $httpCode, $errorCode);
    }
}