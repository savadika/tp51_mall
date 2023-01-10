<?php

/**
 * User:ylf
 * Time:2023/1/10/21:20
 * FileName:BaseController.php
 * Desc:'描述区'
 */


namespace app\api\controller;

use app\api\service\Token as TokenService;
use think\Controller;

class BaseController extends Controller
{
    //权限控制相关

    /**
      用于用户和超级管理员都可以使用的权限
     */
    protected function checkPrimaryScope(){
        TokenService::needPrimaryScope();
    }

    /**
    仅限用户可以使用的权限
     */
    protected function checkUserOnlyScope(){
        TokenService::needUserOnlyScope();
    }



}