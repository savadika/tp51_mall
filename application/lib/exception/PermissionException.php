<?php

/**
 * User:ylf
 * Time:2023/1/10/21:28
 * FileName:PermissionException.php
 * Desc:'描述区'
 */


namespace app\lib\exception;

class PermissionException extends UserException
{
    public function __construct($msg='权限不足', $httpCode = 403, $errorCode = 80000)
    {
        parent::__construct($msg, $httpCode, $errorCode);
    }
}