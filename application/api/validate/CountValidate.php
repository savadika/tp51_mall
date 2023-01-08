<?php

/**
 * User:ylf
 * Time:2022/12/17/21:04
 * FileName:CountValidate.php
 * Desc:'描述区'
 */


namespace app\api\validate;

class CountValidate extends BaseValidate
{
    protected $rule = [
        'count' => 'isPositiveInteger|between:1,15',
    ];

    protected $message = [
        'count' => '数量必须在1-15之间',
    ];
}