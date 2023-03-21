<?php

/**
 * User:ylf
 * Time:2023/1/31/11:08
 * FileName:Pay.php
 * Desc:'描述区'
 */


namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\api\validate\IdMustBeInt;
use app\api\service\Pay as PayService;

/*
 *  微信支付相关接口
 * */
class Pay extends BaseController
{
    //支付相关流程：
    //1  调用微信支付相关的api
    //2  api向微信发送预订单
    //3  微信返回支付参数给小程序

    protected $beforeActionList=[
        'checkUserOnlyScope' =>['only' =>'getPreOrder']
    ];


    // 生成预订单
    // 1 根据订单编号检查当前的库存量
    // 2 调用微信支付的api
    public function getPreOrder($id=''){
        (new IdMustBeInt())->goCheck();
        $pay = new PayService($id);
        $result = $pay->pay();
        return json($result);
    }

}