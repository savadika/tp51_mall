<?php

/**
 * User:ylf
 * Time:2023/3/17/14:05
 * FileName:Notify.php
 * Desc:'描述区'
 */


namespace app\api\controller\v1;

use WeChat\Pay as WeChatPay;

class Notify
{
    //接收微信回调的通知
    public  function sendNotify(){
        $config = config('wepay.wepay_config');
        //  2 实例化支付对象
        $WePreOrder = WeChatPay::instance($config);
        $data = $WePreOrder->getNotify();
        if ($data['return_code'] === 'SUCCESS' && $data['result_code'] === 'SUCCESS') {
            // @todo 去进行减库存的操作，同时将状态改变为已支付
            // 问题：回调的时候我如何获取到订单ID号？



            // 返回接收成功的回复
            ob_clean();
            echo $WePreOrder->getNotifySuccessReply();
        }
    }
}