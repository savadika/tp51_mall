<?php

/**
 * User:ylf
 * Time:2022/10/11/14:37
 * FileName:Sample.php
 * Desc:'描述区'
 */


namespace app\api\controller\v1;

use app\api\validate\BannerValidate;
use think\Validate;

class Sample
{
    /*
    * 独立验证，利用验证器进行
    */
    public function stayAloneValidate(){
        $data = [
            'name' => 'jack',
            'email'=> 'ylf8708126.com'
        ];
        // 构建验证器
        $validate = new validate([
            'name' => 'require|max:3',
            'email'=> 'email'
        ]);
        // 验证结果只有一个
        $result = $validate->check($data);
        echo $validate->getError();
        // 验证结果有多个
        $result2 = $validate->batch()->check($data);
        var_dump($validate->getError());
    }

    /*
    * 基于验证器的验证方式
    */
    public function useValidate(){
        $data = [
            'name' => 'jack',
            'email'=> 'ylf8708126.com'
        ];
        $validate = new TestValidate();
        $result3 = $validate->batch()->check($data);
        var_dump($validate->getError());
    }
}