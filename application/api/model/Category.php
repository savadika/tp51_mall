<?php

/**
 * User:ylf
 * Time:2022/12/18/14:52
 * FileName:Category.php
 * Desc:'描述区'
 */


namespace app\api\model;

class Category extends BaseModel
{
    protected $hidden = ['delete_time', 'update_time','from'];

    //图片模型关联
    public function img(){
        return $this->belongsTo('Image','topic_img_id','id');
    }

    //商品模型关联
    public function products(){
        return $this->belongsToMany('Product', 'Product','id','product_id');
    }


    //获取所有图片分类
    public static  function getAllCategory(){
        return self::all();
    }


}