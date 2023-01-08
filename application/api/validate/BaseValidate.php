<?php

/**
 * User:ylf
 * Time:2022/10/13/16:45
 * FileName:BaseValidate.php
 * Desc:'描述区'
 */


namespace app\api\validate;


use app\lib\exception\ParamsException;
use think\facade\Request;
use think\Exception;
use think\Validate;
use app\lib\exception\UserException;


class BaseValidate extends Validate
{
    /*
    *  利用TP5的方法，对参数进行校验
    */
    public function goCheck(){
        // 使用门面模式中的request可以在别的地方获取到request函数
        $request = Request::instance();
        // 对所有参数进行校验，规则在子类中
        $params = $request->param();
        $result = $this->batch()->check($params);
        if(!$result){
            // 参数错误，通过异常直接中断并抛出
            // 问题来了，异常抛出了，但是给客户端的异常不能这样，如何才能自定义异常？
            throw new ParamsException($this->getError());
        }else{
            return true;
        }
    }

    //验证变量必须是正整数
    protected function isPositiveInteger($value, $rule='', $data='', $filed=''){
        //isset:判断指定变量存在且不为NULL
        if(isset($value)){
            //判断是否为正整数，以正则表达式的方式书写
            if(preg_match("/^[1-9][0-9]*$/",$value) ){
                return true;
            }else{
                return false;
            }
        }
    }

    /*
     * 要求变量非空
     * */
    protected function isNotEmpty($value, $rule='', $data='', $filed=''){
        if(empty($value)){
            return false;
        }else{
            return true;
        }
    }

    /*
     * 参数过滤：要求控制器仅接收rules里面的参数
     * 调用的时候，在控制器中调用即可，参看新增地址接口
     * 1  先看传过来的数据是否包含user_id或者uid，如果存在则抛异常
     * 2  遍历rules,根据rules重新生成数组，然后返回
     * */
    public function  getDataByRules($arrays){
        if(array_key_exists('user_id',$arrays)|array_key_exists('uid',$arrays)){
            throw new ParamsException('非法用户id错误！');
        }else{
            $newArray = [];
            foreach ($this->rule as $key => $value){
                $newArray[$key] = $arrays[$key];
            }
            return $newArray;
        }
    }

}