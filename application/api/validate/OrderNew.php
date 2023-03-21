<?php

/**
 * User:ylf
 * Time:2023/1/12/14:37
 * FileName:OrderNew.php
 * Desc:'描述区'
 */


namespace app\api\validate;


/*
 * 定义复杂验证器
 * 用于验证数据格式是否满足1  数组且嵌套数组 2 单个数组满足验证条件
 * [
 *      ['product_id'=>1,
 *        'count'=>3
 *      ],
 *      [
 *        'product_id'=>2,
 *        'count'=>3
 *      ],
 *      [
 *        'product_id'=>3,
 *        'count'=>4
 *      ]
 * ]
 * */

use app\lib\exception\ParamsException;
use think\facade\Validate;

class OrderNew extends BaseValidate
{
    protected $rules = [];


    protected  $singleRule = [
        'product_id'=>'require|isPositiveInteger',
        'count'=>'require|isPositiveInteger'
    ];

    protected function checkProduct($value){
        $validate = new Validate($this->singleRule);
        $result = $validate->check();
        if($result){
            throw new ParamsException();
        }
        return true;
    }

}