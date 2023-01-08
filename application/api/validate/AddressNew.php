<?php

/**
 * User:ylf
 * Time:2023/1/8/10:51
 * FileName:AddressNew.php
 * Desc:'描述区'
 */


namespace app\api\validate;

class AddressNew extends BaseValidate
{
    protected $rule = [
        'name' => 'require|isNotEmpty',
        'mobile' => 'require|mobile',
        'province' => 'require|isNotEmpty',
        'city' => 'require|isNotEmpty',
        'country' => 'require|isNotEmpty',
        'detail' => 'require|isNotEmpty',
    ];
}