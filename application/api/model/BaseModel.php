<?php

namespace app\api\model;

use think\facade\Config;
use think\Model;

class BaseModel extends Model
{
    // 自动时间戳，可以自动写入到delete_time,update_time
    protected $autoWriteTimestamp = true;
    // 自定义时间戳字段id
    // protected $createTime = 'ylf_update_time';

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
