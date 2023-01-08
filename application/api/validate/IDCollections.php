<?php

/**
 * User:ylf
 * Time:2022/11/25/11:02
 * FileName:IDCollections.php
 * Desc:'描述区'
 */


namespace app\api\validate;

class IDCollections extends BaseValidate
{
    // 规则
    protected $rule = [
        'ids' => 'require|IdsMustBeInt',
    ];

    // 错误信息
    protected $message = [
        'ids' => 'ids必须是以逗分隔的正整数',
    ];

    // 自定义规则，ids = id1,id2,id3.....
    protected function IdsMustBeInt($value){
        $ids_array = explode(',',$value);
        if(empty($ids_array)){
            return false;
        }
        foreach ($ids_array as $id){
            if(!$this->isPositiveInteger($id)){
                return false;
            }
        }
        return true;
    }
}