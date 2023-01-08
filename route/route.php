<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

//获取Banner信息
Route::get('api/:version/banner/:id', 'api/:version.Banner/getBanner');

//获取Theme主题接口
Route::get('api/:version/theme', 'api/:version.Theme/getThemeList');

//获取单个Theme
Route::get('api/:version/theme/:id', 'api/:version.Theme/getOneTheme');

//产品接口
//此处出现一个路由出错的问题，如何精确导航路由,强制开启路由的匹配规则
//获取最近新品
Route::get('api/:version/product/:count', 'api/:version.Product/getNewProduct',[],['count'=>'\d+']);
//获取商品详情
Route::get('api/:version/product/detail', 'api/:version.Product/getProductDetail');




//分类接口
//获取所有商品的分类
Route::get('api/:version/category', 'api/:version.Category/getAllCategory');
//获取商品分类下的商品
Route::get('api/:version/pro_by_category', 'api/:version.Product/getProductsById');


//测试validate类
Route::get('test_single_validate/', 'api/v1.Sample/stayAloneValidate');
Route::get('test_model_validate/', 'api/v1.Sample/useValidate');

//Token接口相关
Route::get('api/:version/token/user', 'api/:version.Token/getUserToken');


//地址接口
Route::post('api/:version/address', 'api/:version.Address/createOrUpdateAddress');


