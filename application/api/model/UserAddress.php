<?php

/**
 * User:ylf
 * Time:2023/1/8/12:15
 * FileName:UserAddress.php
 * Desc:'描述区'
 */


namespace app\api\model;

class UserAddress extends BaseModel
{
    protected $hidden = ['id', 'delete_time','user_id', 'update_time'];
}