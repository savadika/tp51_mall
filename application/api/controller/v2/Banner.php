<?php

/**
 * User:ylf
 * Time:2022/10/10/15:30
 * FileName:Banner.php
 * Desc:'描述区'
 */


namespace app\api\controller\v2;


use app\api\model\Banner as BannerModel;
use app\api\validate\BannerValidate;
use think\Exception;
use app\lib\exception\BannerMissException;

class Banner
{

    /*
     * V2版本的获取Banner信息
     * @url     /banner/:id
     * @http    GET
     * @id      BannerId号
     * */

    public function getBanner($id){
        return 'this is V2 version';
    }


}