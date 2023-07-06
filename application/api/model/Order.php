<?php

/**
 * User:ylf
 * Time:2023/1/27/15:31
 * FileName:Order.php
 * Desc:'描述区'
 * 类命名：驼峰法，首字母大写  BaseModel
 * 函数命名：驼峰法，首字母小写 getSummaryByUid
 * 属性命名：首字母小写  protected $userName
 */


namespace app\api\model;

use think\Paginator;




class Order extends BaseModel
{
    //获取用户订单分页数据,使用简洁模式
    public function getSummaryByUid($uid,$page=1,$size=5){
        //返回的是Pagination对象,true代表简洁模式
        $pagData =  self::where('user_id','=', $uid )
            ->paginate($size,true,[
                'page'=>$page
            ]);
        return $pagData;
    }

    //利用模型的读取器对数据进行转换
    //json_decode将字符串转换成php变量
    public function getSnapItemsAttr($value){
        return json_decode($value);
    }

    public function getSnapAddressAttr($value){
        return json_decode($value);
    }


    //根据id获取具体的订单详情
    public function getDetailOrder($id){
        $data = self::where('id','=',$id)
            ->hidden(['delete_time','create_time'])
            ->selectOrFail();
        return $data;
    }

}