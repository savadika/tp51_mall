<?php

/**
 * User:ylf
 * Time:2023/1/22/10:42
 * FileName:OrderValidate.php
 * Desc:'描述区'
 */


namespace app\api\validate;

use app\lib\exception\ParamsException;
use think\facade\Validate;

class OrderValidate extends BaseValidate
{
    // 对订单数据进行校验
    // 两层校验，验证单层及多层，先对外层进行校验，再对内部进行校验
    // 外部规则，必须是一个数组
    protected $rule = [
        'products'=>'checkProducts'
    ];

    // 单条数据验证规则
    protected $singleRule = [
        'id'=>'require|isPositiveInteger',
        'count'=>'require|isPositiveInteger'
    ];

    // 自定义验证规则
    protected function checkProducts($values){
        //自定义多层数组的校验规则
        if(empty($values)){
            throw new ParamsException('参数不能为空');
        }
        if(!is_array($values)){
            throw new ParamsException('参数必须是数组格式');
        }
        foreach ($values as $value){
            $this->checkSingleProduct($value);
        }
        return true;
    }

    // 验证单个产品的参数是否符合要求
    private function checkSingleProduct($value){
        // 独立使用验证器
        $validate = new BaseValidate($this->singleRule);
        $result = $validate->check($value);
        if(!$result){
            throw new ParamsException('商品参数不正确');
        }
    }


}