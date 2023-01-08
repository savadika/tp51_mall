<?php

/**
 * User:ylf
 * Time:2023/1/4/11:15
 * FileName:ProductImage.php
 * Desc:'描述区'
 */


namespace app\api\model;

class ProductImage  extends BaseModel
{
    protected $hidden=['delete_time','product_id'];

    public function imageUrl(){
        return $this->belongsTo('Image','img_id','id');
    }
}