<?php

/**
 * User:ylf
 * Time:2022/10/19/15:31
 * FileName:Banner.php
 * Desc:'描述区'
 */


namespace app\api\model;

use app\api\validate\TestValidate;
use think\Db;
use think\Exception;
use app\lib\exception\BannerMissException;
use think\Model;

class Banner extends Model
{
    /*
     * 关联函数 一对多
     * 查询Banner对应的Banner_item
     * */
    protected $hidden = ['delete_time', 'update_time'];
    public function items(){
        return $this->hasMany('BannerItem', 'banner_id', 'id');
    }

}