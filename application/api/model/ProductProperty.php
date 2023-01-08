<?php

/**
 * User:ylf
 * Time:2023/1/7/19:23
 * FileName:ProductProperty.php
 * Desc:'描述区'
 */


namespace app\api\model;

class ProductProperty extends BaseModel
{
    protected  $hidden=['product_id','delete_time','update_time'];
}