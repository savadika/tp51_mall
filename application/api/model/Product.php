<?php

namespace app\api\model;

use think\Collection;
use think\Model;

class Product extends BaseModel
{
    //  隐藏信息
    protected $hidden = ['delete_time', 'create_time', 'update_time', 'pivot' ,'from','category_id'];


    //  定义关联关系，主图
    public function mainImg(){
        return $this->belongsToMany('Image','product_image', 'img_id', 'product_id');
    }

    //  定义一对多关系,产品图片
    public function productImg(){
        return $this->hasMany('ProductImage','product_id','id');
    }

    // 定义一对多关系，产品属性
    public function properties(){
        return $this->hasMany('ProductProperty','product_id','id');
    }


    //  增删改查
    
    //  读取器 main_img_url
    public function getMainImgUrlAttr($value, $data){
        // 读取器灵活调用基类的前缀方法进行调用
        return $this->prefixImage($value, $data);
    }

    public static function getLast($count){
        // 获取最新的15条产品数据
        return self::limit($count)->order('create_time desc')->select();
    }

    public static function getProductsByCategoryId($id){
        $products = self::where('category_id','=',$id)->select();
        return $products;
    }

    /*返回单个产品的详情信息，嵌套查询*/
    /*1  注意这种嵌套查询的写法，会同时查询productImg以及imageUrl,不需要定义两次*/
    /*2  注意这种嵌套查询后进一步的查询优化写法*/
    public static function getProductDetail($id){
//        方法1 ：仅嵌套的写法
//        $product = self::with(['properties','productImg.imageUrl'])
//            ->where('id','=',$id)->findOrFail();
//        方法2：嵌套+关联字段排序,两个字段只能写在一起
        $product = self::with(['properties','productImg'=>function($query){
            $query->with('imageUrl')->order('order','asc');
        }])
            ->where('id','=',$id)->findOrFail();
        return $product;
    }

}
