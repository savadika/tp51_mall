<?php

/**
 * User:ylf
 * Time:2022/10/17/15:53
 * FileName:UserException.php
 * Desc: '基础异常类-用户输入造成的异常处理'
 */


namespace app\lib\exception;

use think\Exception;

class UserException extends Exception
{
    // httpCode:返回的请求码【哪一类的错误】，msg:错误信息，$errorCode:自定义错误码【具体的错误类型】
    public $httpCode ;
    public $msg ;
    public $errorCode ;
    public function __construct($msg, $httpCode=400, $errorCode=10000)
    {
        //     构造函数
       $this->httpCode = $httpCode;
       $this->msg = $msg;
       $this->errorCode = $errorCode;
    }
}