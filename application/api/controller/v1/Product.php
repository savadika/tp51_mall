<?php

/**
 * User:ylf
 * Time:2022/12/17/20:36
 * FileName:Product.php
 * Desc:'描述区'
 */


namespace app\api\controller\v1;
use app\api\model\Product as ProductModel;
use app\api\validate\CountValidate;
use app\api\validate\IdMustBeInt;
use app\lib\exception\ParamsException;
use app\lib\exception\ProductException;


class Product
{
    //获取新品
    public function getNewProduct($count){
        //校验-取值-判空异常处理-返回
        (new CountValidate()) ->goCheck();
        $products = ProductModel::getLast($count);
        $products->hidden(['summary']);
        if($products->isEmpty()){
            throw new ProductException();
        }
        return json($products);
    }

    /*
     * 获取商品分类下的所有商品
     * **/
    public function getProductsById($id){
        (new IdMustBeInt())->goCheck();
        $products = ProductModel::getProductsByCategoryId($id);
        if($products->isEmpty()){
            throw new ProductException();
        }
        $products->hidden(['summary']);
        return $products;
    }

    /*
     * 获取某个商品的详细信息
     * */
    public function getProductDetail($id){
        (new IdMustBeInt())->goCheck();
        $product = ProductModel::getProductDetail($id);
        if($product->isEmpty()){
            throw new ParamsException('商品信息错误！');
        }
        return $product;
    }


}