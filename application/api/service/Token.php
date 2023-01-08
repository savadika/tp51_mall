<?php

/**
 * User:ylf
 * Time:2022/12/23/14:33
 * FileName:Token.php
 * Desc:'描述区'
 */


namespace app\api\service;

use app\lib\exception\TokenException;
use think\Exception;
use think\facade\Cache;
use think\facade\Request;

class Token
{
        /*
         *  token生成函数
         * **/
        public function grantToken(){
            $randChar =GetRandStr(32);
            $timestamp = $_SERVER['REQUEST_TIME_FLOAT'];
            $salt = config('secure.token_salt');
            return md5($randChar.$timestamp.$salt);
        }

        /*
         *  根据token获取想要的缓存数据，可换各种
         *  1  获取到token
         *  2  判断缓存中是否有token,没有抛异常
         *  3  有，根据key判断有没有值，没有抛异常
         *  4  获取值，返回
         * */
        public static function getCacheValueByToken($key){
            $token = Request::instance()->header('token');
            $vars = Cache::get($token);
            if(!$vars){
                throw new TokenException();
            }
            if(!is_array($vars)){
                json_decode($vars,true);
            } else{
                if(array_key_exists($key,$vars)){
                    return $vars[$key];
                }else{
                    throw new Exception('尝试获取的token值不存在！');
                }
            }
        }

        /*
         * 获取用户uid
         * */
        public static function getUidByToken(){
            $uid =  self::getCacheValueByToken('uid');
            return $uid;
        }


}