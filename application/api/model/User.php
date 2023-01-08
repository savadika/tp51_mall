<?php

/**
 * User:ylf
 * Time:2022/12/31/12:04
 * FileName:User.php
 * Desc:'描述区'
 */


namespace app\api\model;

class User extends BaseModel
{
    /*
     * 定义关联关系
     * */
    public function address(){
        return $this->hasOne('UserAddress','user_id','id');
    }
}