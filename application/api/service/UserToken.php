<?php

/**
 * User:ylf
 * Time:2022/12/23/14:34
 * FileName:UserToken.php
 * Desc:'描述区'
 */


namespace app\api\service;
use app\lib\enum\scopeEnum;
use app\lib\exception\CacheException;
use app\lib\exception\TokenException;
use app\api\model\User as UserModel;
use think\facade\Cache;

class UserToken extends Token
{
    //处理关于用户Token相关的类
    //思路：1  构造UserToken类，以便外部调用
    //     2  向微信服务器请求，获取WxResult
    protected $APPID='';
    protected $APPSECRECT='';
    protected $CODE= '';
    protected $WX_LOGIN_URL='';

    public function __construct($code){
        /*构造函数*/
        $this->APPID = config('token.APP_ID');
        $this->APPSECRECT = config('token.APP_SECRET');
        $this->CODE = $code;
        //拼接成微信的请求地址
        $this->WX_LOGIN_URL=sprintf(config('token.WX_LOGIN_URL'),
            $this->APPID,$this->APPSECRECT,$this->CODE);
    }

    /*
     * UseToken的主要函数，等同于登录
     * 1  根据微信请求地址，生成WxResult{openid,session_key,exprisess_in}，根据返回的格式做处理
     * 2  将openid存入数据库，生成一条数据库的记录，如果存在，则不操作（nickname怎么处理？），返回一个uid
     * 3  准备缓存数据：{WxResult(微信返回数据),uid（用户id）,scope(权限)}
     * 4  生成token，token的生成算法，根据md5,加密各种盐等生成
     * 5  生成键值对{token:value}，存入TP的文件系统
     * 6  将token以键值对方式{token:tokenValue}的形式返回给小程序
     */
    public function get(){
//        print($this->WX_LOGIN_URL);
        $wxResult = curl_get($this->WX_LOGIN_URL);
        $wxResult = json_decode($wxResult, true);
        $result = self::checkWxResult($wxResult);
        if($result){
            //校验通过
            $openid = $wxResult['openid'];
            $uid = self::getUidByOpenid($openid);
            // 准备数据
            $token = $this->grantToken();
            $cacheValue = self::prepareCacheValue($wxResult,$uid);
            $exprise_time = config('secure.exprise_in');
            // 存入缓存
            $this->saveToCache($token,$cacheValue,$exprise_time);
            // 返回给小程序
            return $token;
        }
        else{
            throw new TokenException();
        }
    }

    /*
     * 检查返回回来的微信格式是否正确
     * **/
    protected static function checkWxResult($wxResult){
        // 将json转换为数组
        if(array_key_exists('errcode',$wxResult)){
            return false;
        }
        else{
            return true;
        }
    }

    /*
     * 根据用户的openid去判断数据库记录，并返回uid
     * */
    protected static function getUidByOpenid($openid){
        $user = UserModel::where('openid','=',$openid)->find();
        $uid = '';
        if($user){
            $uid  = $user['id'];
        }
        else{
            //新增单个用户数据
            $newuser = new UserModel();
            $data = ['openid'=>$openid];
            $uid = $newuser->insertGetId($data);
        }
        return $uid;
    }

    /*
     * 准备wxResult的返回数据
     * */
    protected static function prepareCacheValue($wxResult,$uid){
        $wxResult['uid'] = $uid;
        $wxResult['scope'] = scopeEnum::user;
        return $wxResult;
    }

    /*
     * 将数值存入到TP5的缓存中
     * */
    private function saveToCache($key,$value,$exprise_time){
        $result = Cache::set($key,$value,$exprise_time);
        if($result){
            return true;
        }
        else{
            return new CacheException();
        }
    }



}