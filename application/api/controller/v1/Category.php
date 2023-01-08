<?php

/**
 * User:ylf
 * Time:2022/12/18/14:43
 * FileName:Category.php
 * Desc:'描述区'
 */


namespace app\api\controller\v1;
use app\api\model\Category as CategoryModel;
use app\api\validate\IdMustBeInt;
use app\lib\exception\CategoryException;


class Category
{
    //获取所有的分类列表
    public function getAllCategory(){
        $categorys = CategoryModel::with(['img'])->all();
        if($categorys->isEmpty()){
            throw new CategoryException();
        }
        return $categorys;
    }


}