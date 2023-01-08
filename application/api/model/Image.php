<?php

namespace app\api\model;

use think\Model;
use think\facade\Config;

class Image extends BaseModel
{
    // 设置最终返回json格式中需要隐藏的字段
    protected $hidden = ['id','from', 'update_time', 'delete_time'];
    // 利用读取器读取图片的真正地址,读取器格式：get+字段名+Attr
    // 读取器是在什么情况下生效的？ 就是当Image->url，模型读取字段的时候触发
    public function getUrlAttr($value, $data){
       // 读取器灵活调用基类的前缀方法进行调用
       return $this->prefixImage($value, $data);
    }
}
