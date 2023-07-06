<?php

/**
 * User:ylf
 * Time:2023/1/22/09:39
 * FileName:Order.php
 * Desc:'下单控制器'
 */


namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\validate\IdMustBeInt;
use app\api\validate\OrderSummaryValidate;
use app\api\validate\OrderValidate;
use app\api\service\Order as orderService;
use think\facade\Request;
use app\api\service\Token as tokenService;
use app\api\model\Order as OrderModel;

class Order extends BaseController
{
    /*
     * 要完整地完成订单下单的功能，需要以下几个步骤
     * 总计：3次的库存量检测
     * 1  下单
     *     1.1  对前端传递过来的参数进行校验
     *      $products= [
     *         [
     *              'id'=> 1,
     *              'count' => 2
     *         ],
     *          [
     *              'id'=> 3,
     *              'count' => 4
     *         ],
     *       ]
     *     1.2  进行库存量检测，库存量status
     *     1.3  下单，将订单数据存入数据库,并将状态返回给客户端
     * 2  支付
     *     2.1  客户端调用服务器的支付接口进行支付
     *     2.2  服务器进行库存量检测，检测通过
     *     2.3  服务器调用微信端的支付接口进行支付
     *     2.4  微信返回支付结果|成功：进行库存量检查及扣除|失败：改变订单状态
     * 3  通知
     *     3.1 暂定
     * */

    //定义权限,仅用户权限可以使用
    protected $beforeActionList = [
        // checkPrimaryScope前置方法将作用于createOrUpdateAddress
        'checkUserOnlyScope' =>['only' =>'placeOrder']
    ];

    public function placeOrder(){
        (new OrderValidate())->goCheck();
        // 利用助手函数获取到传递过来的参数并数组化
        // 问题：input函数为什么不起作用
        $products = Request::param('products');
        // 下单
        $orderService = new orderService($products);
        $data = $orderService->place($products);
        return json($data);
    }


    // 获取订单列表接口    orders?page=1&size=5
    public function getSummaryOrders($page=1,$size=5){
        (new OrderSummaryValidate())->goCheck();
        // 通过token去获取用户的uid
        $uid = tokenService::getUidByToken();
        $orderModel = new OrderModel();
        $pagData = $orderModel->getSummaryByUid($uid,$page,$size);
        if($pagData->isEmpty()){
            return null;
        }
        $data = [
            'current_page'=>$pagData->getCurrentPage(),
            //问题：data:data下面的才有snap_items等数据
            'data'=>$pagData->hidden(['snap_items','snap_address','perpay_id'])->toArray()
        ];
        return json($data);
    }


    // 返回orderDetail的具体信息
    public function getOrderDetail($id){
        (new IdMustBeInt())->goCheck();
        $ordermodel = new OrderModel();
        $result =$ordermodel->getDetailOrder($id);
        if($result->isEmpty()){
            return null;
        }
        return json($result);
    }



}