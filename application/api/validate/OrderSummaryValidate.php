<?php
/**
 * User:ylf
 * Date:2023/7/3
 * FileName:OrderSummaryValidate.php
 */

namespace app\api\validate;

class OrderSummaryValidate extends BaseValidate{

    protected $rule = [
        'page' =>  'isPositiveInteger',
        'size' =>  'isPositiveInteger',
    ];

    protected $message = [
        'page' => '页数必须是正整数',
        'size' => '页数必须是正整数',
    ];


}