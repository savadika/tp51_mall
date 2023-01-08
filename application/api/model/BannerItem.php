<?php

namespace app\api\model;

use think\Model;

class BannerItem extends Model
{
    protected $hidden = ['id', 'img_id', 'delete_time', 'banner_id', 'update_time'];
    //建立与image之间的关联模型
    public function img(){
        return $this->belongsTo('Image','img_id','id');
    }
}
