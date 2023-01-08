<?php

/**
 * User:ylf
 * Time:2022/12/29/12:49
 * FileName:TokenValidate.php
 * Desc:'描述区'
 */


namespace app\api\validate;

class TokenValidate extends BaseValidate
{
        protected $rule = ['code' => 'require'];
        protected $message = [
            'code' => '码错误！'
        ];

}