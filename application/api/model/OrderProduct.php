<?php

/**
 * User:ylf
 * Time:2023/1/29/16:13
 * FileName:OrderProduct.php
 * Desc:'描述区'
 */


namespace app\api\model;

class OrderProduct extends BaseModel
{
    //根据订单编号去获取order数据
    public static function getOrderProducts($orderID){
        $op = self::where('order_id','=',$orderID)
            ->visible(['product_id','count'])
            ->selectOrFail()
            ->toArray();
        return $op;
    }



}