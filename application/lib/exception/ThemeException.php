<?php

/**
 * User:ylf
 * Time:2022/11/25/13:01
 * FileName:ThemeException.php
 * Desc:'描述区'
 */


namespace app\lib\exception;


class ThemeException extends UserException
{
    public function __construct($msg='ID错误，请检查主题ID是否存在', $httpCode = 401, $errorCode = 10002)
    {
        parent::__construct($msg, $httpCode, $errorCode);
    }
}