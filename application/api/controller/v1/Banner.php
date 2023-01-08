<?php

/**
 * User:ylf
 * Time:2022/10/10/15:30
 * FileName:Banner.php
 * Desc:'描述区'
 */


namespace app\api\controller\v1;


use app\api\model\Banner as BannerModel;
use app\api\validate\BannerValidate;
use think\Exception;
use app\lib\exception\BannerMissException;

class Banner
{

    /*
     * 获取Banner信息
     * @url     /banner/:id
     * @http    GET
     * @id      BannerId号
     * */
    public function getBanner($id){
        // 校验
        (new BannerValidate()) ->goCheck();
        // 使用ORM的方式进行调用,查询关联模型,items.img是模型嵌套
        $banner = BannerModel::with(['items','items.img'])->find($id);
        if($banner->isEmpty()){
            throw new Exception('错了');
        }else{
            return json($banner);
        }
    }
}