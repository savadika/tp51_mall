<?php

/**
 * User:ylf
 * Time:2023/1/22/13:53
 * FileName:Order.php
 * Desc:'描述区'
 */


namespace app\api\service;
use app\api\model\OrderProduct;
use \app\api\model\Product as ProductModel;
use app\api\model\UserAddress as UserAddressModel;
use app\api\model\Order as OrderModel;
use \app\api\service\Token as TokenService;
use app\lib\enum\orderStatusEnum;
use app\lib\exception\UserException;
use think\Db;
use think\Exception;

class Order
{
    //定义内置变量，方便各个方法进行调用 订单产品  数据库产品 用户id 比较信息
    protected $order_products;
    protected $db_products;
    protected $order_ids;
    protected $uid;

    /*
     * 核心下单方法
     * 1  根据orderProducts 遍历出 order_id数组
     * 2  根据order_id数组找出对应的数据
     * 3  判断是否存在库存量超出的情况，最终返回status
     *  $status = [
     *      'pass' =>true,
     *      'total_price'=>0,
     *      'pstatus'=>[
     *           ['id','haveStock','count','name','totoal_price'],
     *           ['id','haveStock','count','name','totoal_price'],
     *      ]
     * ]
     * 4  如果通过，则写入订单快照（含订单+单体json数据+收货地址）
     * */

    public function __construct($order_products)
    {
        $this->order_products = $order_products;
        $order_ids = [];
        foreach ($order_products as $p){
            $order_ids[]= $p['id'];
        }
        $this->order_ids = $order_ids;
        // 获取数据库真实数据
        $products = ProductModel::all($this->order_ids)
            ->visible(['id', 'price', 'stock', 'name', 'main_img_url'])
            ->toArray();
        $this->db_products = $products;
        $this->uid = TokenService::getUidByToken();
    }


    public function place($order_products){
        // 根据订单查询库存状态
        $allstatus = $this->getProductsStatus($this->order_products);
        // 如果库存量检测未通过，则直接返回status
        if($allstatus['pass']==false){
            $allstatus['order_no']=-1;
            return $allstatus;
        }
        else{
            // 创建订单快照,存入数据库，创建订单编号，返回$allstatus
           return $this->snapOrder($allstatus);
        }
    }

    /**
     * ************************************库存量检测相关函数*******************************
     */


    // place辅助方法1：检查所有产品状态
    public function getProductsStatus($order_products){
        // 定义最终要返回的数据结构
        $allstatus = [
            'pass'=>true,
            'order_no'=>'',
            'total_price'=>0,
            'total_count'=>0,
            'pstatus'=>[]
        ];
        //计算pstatus的状态
        $allstatus = $this->getPStatus($this->order_products,$this->db_products,$allstatus);
        //再次计算allstatus状态
        foreach ($allstatus['pstatus'] as $oneStatu){
            if($oneStatu['haveStock']==false){
                $allstatus['pass'] = false;
            }
            $allstatus['total_price'] +=$oneStatu['total_price'];
            $allstatus['total_count'] +=$oneStatu['count'];
        }
        return $allstatus;
    }

    // place辅助方法2：获取Pstatus的数值
    //        'pstatus'=>[
    //             ['id','haveStock','count','name','totoal_price'],
    //             ['id','haveStock','count','name','totoal_price'],
    //           ]
    protected function getPStatus($order_products,$db_products,$allstatus){
            foreach ($order_products as $op){
                foreach ($db_products as $dp){
                    if ($op['id']==$dp['id']){
                        if($op['count']>=$dp['stock']){
                            $arr = [
                                'id'=>$dp['id'],
                                'haveStock'=>false,
                                'count'=>$op['count'],
                                'name'=>$dp['name'],
                                'price'=>$dp['price'],
                                'total_price'=>$dp['price']*$op['count']
                            ];
                            array_push($allstatus['pstatus'],$arr);
                        }else{
                            $arr = [
                                'id'=>$dp['id'],
                                'haveStock'=>true,
                                'count'=>$op['count'],
                                'name'=>$dp['name'],
                                'price'=>$dp['price'],
                                'total_price'=>$dp['price']*$op['count']
                            ];
                            array_push($allstatus['pstatus'],$arr);
                        }
                    }
                }
            }
            return $allstatus;
    }




    /**
    * ************************************下单相关函数*******************************
     */

    //生成订单编号
    public function makeOrderId(){
        $yCode = array('A','B','C','D','E','F','G','H','I','J','K','L',);
        $orderSn = $yCode[intval(date('Y')-2017)].strtoupper(dechex(date('m'))).date(
            'd').substr(time(),-5).substr(microtime(),2,5).sprintf(
                '%02d',rand(0,99));
        return $orderSn;
       }

    // 生出快照的用户地址
    protected function getUserAdderss(){
        $user_address = UserAddressModel::where('user_id','=',$this->uid)->findOrFail();
        if(empty($user_address)){
            throw  new UserException('用户收货地址不存在,请先添加！',40003);
        }
        return $user_address->toArray();
    }



    //创建订单快照
    protected function snapOrder($allstatus){
        //定义基本的快照结构
        $snap=[
            'order_no' => '',
            'user_id'=>'',
            'total_pirce'=>0,
            'status'=>1,
            'snap_img'=>'',
            'snap_name'=>'',
            'total_count'=>'',
            'snap_items'=>'',
            'snap_address'=>''
        ];
        $snap['order_no']=$this->makeOrderId();
        $snap['user_id']=$this->uid;
        $snap['total_pirce']=$allstatus['total_price'];
        $snap['snap_img'] = $this->db_products[0]['main_img_url'];
        $snap['snap_name']=$this->db_products[0]['name'];
        $snap['total_count']=$allstatus['total_count'];
        $snap['snap_items'] = $allstatus['pstatus'];
        $snap['snap_address'] = json_encode($this->getUserAdderss());
        $snap['status']=orderStatusEnum::UNPAID;
        //修正名字
        if(count($this->db_products)>1){
            $snap['snap_name'] .='等';
        }
        return $this->saveSnap($snap);
    }

    // 写入快照信息到order表，同时写入到一对多的数据
    protected function saveSnap($snap){
        try
        {
            // 开启事务，保证整个操作是连贯的
            Db::startTrans();
            $order = new OrderModel();
            $order->order_no = $snap['order_no'];
            $order->user_id = $snap['user_id'];
            $order->total_price = $snap['total_pirce'];
            $order->snap_img = $snap['snap_img'];
            $order->snap_name = $snap['snap_name'];
            $order->total_count = $snap['total_count'];
            $order->snap_items = json_encode($snap['snap_items']);
            $order->status = $snap['status'];
            $order->snap_address = $snap['snap_address'];
            $order->save();
            //写入到一对多的数据表中
            $data = $this->insertOrderProduct($order);
            //提交事务
            Db::commit();
            return $data;
        }catch (Exception $e){
            // 如果失败，进行事务的回滚
            Db::rollback();
            throw $e;
        }

    }

    // 写入到一对多的order_product表
    protected function insertOrderProduct($order){
        $orderID = $order->id;
        //  foreach数组并修改数组值，需要使用&
        //  不同的名字访问同一个变量内容
        foreach ($this->order_products as &$p){
            $p['order_id'] = $orderID;
        }
        //对$this->products进行优化
        $tempArray = $this->order_products;
        foreach ($tempArray as &$t){
            $t['product_id'] = $t['id'];
            unset($t['id']);
        }
        //新增一对多数据
        $orderProduct = new OrderProduct();
        $orderProduct->saveAll($tempArray);
        $data = [
            'order_id'=>$orderID,
            'order_no'=>$order->order_no,
            'create_time'=>$order->create_time,
            'pass'=>true
        ];
        return $data;
    }
}