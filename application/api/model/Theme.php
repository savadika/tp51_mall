<?php

namespace app\api\model;

use think\Model;

class Theme extends BaseModel
{
    // 定义需要隐藏的字段
    protected $hidden = ['delete_time', 'update_time', 'topic_img_id', 'head_img_id'];

    //===================================定义关联关系==================================
    // 定义theme与image的关系，一对多关系
    // belongsTo ,有外键的一方用，反之，则用hasOne

    public function topicImg(){
        return $this->belongsTo('Image', 'topic_img_id', 'id');
    }
    public function headImg(){
        return $this->belongsTo('Image', 'head_img_id','id');
    }

    // 定义与products之间的多对多关系
    public function products(){
        // 特别要注意多对多之间的写法，第二个是模型，第三个是外键，第四个是主键
        return $this->belongsToMany('Product', 'theme_product','product_id', 'theme_id');
    }


    //===================================查询关联关系==================================
    public function getThemes($id_array){
        return self::with('topicImg,headImg')->select($id_array);
    }


    public function getThemeWithProducts($theme_id){
        //注意，with中使用的就是上面定义的关系
       return self::with(['products','topicImg','headImg'])->find($theme_id);
    }


}
