<?php

/**
 * User:ylf
 * Time:2023/3/3/09:50
 * FileName:orderStatusEnum.php
 * Desc:'描述区'
 */


namespace app\lib\enum;

class orderStatusEnum
{
    const UNPAID = 1;   //待支付
    const PAID = 2;  //已支付
    const DELIVERED =3; //已发货
    const PAID_BUT_OUT_OF_STORE=4; //已支付无库存
    const HAND_OUT_OF_STORE=5 ;  //已处理无库存
}