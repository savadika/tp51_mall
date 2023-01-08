<?php

namespace app\api\model;

use think\facade\Config;
use think\Model;

class BaseModel extends Model
{
    //增加图片前缀,此方法修改为protected，便于子类的调用
    protected function prefixImage($value, $data){
        $finalUrl = $value;
        if ($data['from'] == 1){
            // 如果图片来源于本地，则自动拼合
            $finalUrl = Config::get('settings.img_prefix').$value;
        }
        return $finalUrl;
    }
}
