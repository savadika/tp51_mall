<?php

/**
 * User:ylf
 * Time:2022/11/26/22:40
 * FileName:IdMustBeInt.php
 * Desc:'描述区'
 */


namespace app\api\validate;

class IdMustBeInt extends BaseValidate
{
    // 定义验证器,id只能为大于0的整数
    //自 5.4 起可以使用短数组定义语法，用 [] 替代 array()。
    protected $rule = [
        'id' => 'require|isPositiveInteger',
    ];

    // 自定义错误的信息
    protected $message = [
        'id' => 'id必须是正整数,不然不给过哦',
    ];
}