<?php

/**
 * User:ylf
 * Time:2022/12/29/12:33
 * FileName:Token.php
 * Desc:'描述区'
 */


namespace app\api\controller\v1;

use app\api\service\UserToken;
use app\api\validate\TokenValidate;
use think\response\Jsonp;

class Token
{
    public function getUserToken($code){
        /**思路
         1  验证客户端的code是否正确
         2  实例化UserToken类，并调用方法获取token值
         3  返回给客户端token键值对
         *
         * 作用：
         * 1  向用户数据库新增openid用户数据
         * 2  生成token，返回给小程序进行存储
         * 3  生成键值对，存储在TP的缓存中，{token:{wxResult,uid,scope}}
         */
        (new TokenValidate())->goCheck();
        $userToken = new UserToken($code);
        $token = $userToken->get();
        return json(["token"=>$token]);
    }
}