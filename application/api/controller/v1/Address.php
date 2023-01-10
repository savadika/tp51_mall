<?php

/**
 * User:ylf
 * Time:2023/1/8/10:46
 * FileName:Address.php
 * Desc:'描述区'
 */


namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\api\validate\AddressNew;
use app\lib\exception\SuccessMsg;
use app\lib\exception\UserException;
use think\Controller;
use app\api\service\Token as TokenService;
use app\api\model\User as UserModel;
use app\api\model\UserAddress as UserAddressModel;

class Address extends BaseController
{

    //定义权限
    protected $beforeActionList = [
        // checkPrimaryScope前置方法将作用于createOrUpdateAddress
        'checkPrimaryScope' =>['only' =>'createOrUpdateAddress']
    ];


    /*
     * 新增用户地址接口，需要调用token
     * */
    public function createOrUpdateAddress(){
//        1  获取到用户的uid
//        2  用uid去判断用户数据库是否存在该用户
//        3  不存在抛异常，存在的话判断地址是否存在，存在则更新，否则新增
        $validate = new AddressNew();
        // 校验规则看是否有效
        $validate->goCheck();
        // 获取到所有只有rules规则中定义了的值，不接受其他
        $dataArray = $validate->getDataByRules(input('post.'));
        $uid = TokenService::getUidByToken();
        $user = UserModel::get($uid);
        if(!$user){
            throw new UserException('用户未找到！');
        }else{
            $address = $user->address;
            if(!$address){
                //新增
                $user->address()->save($dataArray);
            }else{
                //修改
                $user->address->allowField(true)->save($dataArray);
            }
            return json_encode(new SuccessMsg());
        }
    }


}