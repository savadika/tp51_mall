<?php

/**
 * User:ylf
 * Time:2023/1/31/15:27
 * FileName:Pay.php
 * Desc:'描述区'
 */


namespace app\api\service;

use app\api\model\OrderProduct;
use app\api\model\Order as OrderModel;
use app\lib\enum\orderStatusEnum;
use app\lib\exception\OrderException;
use app\api\service\Token as TokenService;
use app\lib\exception\TokenException;
//加载微信支付相关sdk
use \WeChat\Pay as WeChatPay;


class Pay
{
    protected $orderID;
    //具体的支付接口
    public function __construct($orderID)
    {
        $this->orderID = $orderID;
    }

    public function pay(){
        //判断订单id的有效性
        $this->checkIdValid($this->orderID);
        //对订单的库存量检测
        $op = OrderProduct::getOrderProducts($this->orderID);
        $new_op=changeIdName($op);
        $order = new Order($new_op);
        $status = $order->getProductsStatus($new_op);
        //如果没有库存，返回给前端自己处理
        if(!$status['pass']){
            return $status;
        }
        //向微信发起支付请求
        $WxPreResult =  $this->makeWxPreOrder($status);
        return $WxPreResult;
    }


    private function makeWxPreOrder($status){
        $openid = TokenService::getCacheValueByToken('openid');
        if(!$openid){
            throw new TokenException('用户异常');
        }
        // 1 配置支付基本参数
        $config = config('wepay.wepay_config');
        //  2 实例化支付对象
        $WePreOrder = WeChatPay::instance($config);

        //  3 配置预订单对象参数
        $options = [
            'body'             => '商品对象',
            'out_trade_no'     => time(),
            'total_fee'        => $status['total_price']*100,
            'openid'           => TokenService::getCacheValueByToken('openid'),
            'trade_type'       => 'JSAPI', // JSAPI--JSAPI支付（服务号或小程序支付）、NATIVE--Native 支付、APP--APP支付，MWEB--H5支付
            // 支付的回调地址，指微信服务器向商家服务器发送的消息，用于后续操作
            // 比如，支付成功以后，扣除库存，给用户充值等
            'notify_url'       => 'https://2b95abd2.r3.vip.cpolar.cn/api/v1/notify',
            'spbill_create_ip' => '127.0.0.1',
        ];

        //  4 调用微信支付函数进行预订单创建
        $result = $WePreOrder->createOrder($options);
        //  如果创建成功，那就会有prepad_id，将这个id存入到数据库中
        if($result['prepay_id']){
           $order = OrderModel::where('id','=',$this->orderID)->findOrFail();
           $order->prepay_id = $result['prepay_id'];
           $order->save();
        }else{
            throw new OrderException('未成功创建预订单');
        }

        //  5 生成微信要用的参数
        $params = $WePreOrder->createParamsForJsApi($result['prepay_id']);
        return $params;
    }




    //判断id在业务层面是否有效
    // 1  id是否在数据库中
    // 2  id穿过来的用户是否就是使用者本人
    // 3  订单是未支付过的
    private  function checkIdValid($id){
        $order_model = OrderModel::where('id','=',$id)->findOrFail();
        $uid = $order_model->user_id;
        if(!$order_model){
            throw new OrderException();
        }

        if($uid){
            if(!TokenService::isValidUser($uid)){
                throw new OrderException('当前用户异常，请检查！');
            }
        }

        if($order_model->status != orderStatusEnum::UNPAID){
            throw new OrderException('当前订单已支付',400,90001);
        }

        return true;
    }


}