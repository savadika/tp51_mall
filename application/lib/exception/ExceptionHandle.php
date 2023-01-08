<?php

/**
 * User:ylf
 * Time:2022/10/17/15:52
 * FileName:ExceptionHandle.php
 * Desc:'全局异常处理器'
 */


namespace app\lib\exception;

use Exception;
use think\exception\Handle;
use app\lib\exception\UserException;
use think\Request;
use think\facade\Log;

class ExceptionHandle extends Handle
{
    //要对全局异常进行接管，需要两步
    //1 自定义render方法，对异常进行处理
    //2 配置app.php中的exception_handle进行设置，进行接管

    private $httpCode;
    private $msg;
    private $errorCode;

    public function render(Exception $e){
        $request = \think\facade\Request::instance();
        if ($e instanceof UserException){
            // 自定义异常处理，不需要记录
            $this->httpCode = $e->httpCode;
            $this->msg = $e->msg;
            $this->errorCode = $e->errorCode;
        }else{
            // 服务器异常
            // 开发阶段：直接展示错误，上线阶段：显示异常信息
            if(config('app_debug')){
                // 直接调用父类的方法
                return parent::render($e);
            }else{
                $this->httpCode = 500;
                $this->msg = '服务器内部错误';
                $this->errorCode = 999;
            }
            // 如果是服务器异常，需要进行记录
            $this->recordMessage($e);
        }
        $result = [
            'msg' => $this->msg,
            'errorCode' => $this->errorCode,
            'request_url' => $request->url()
        ];
        // 最终返回给客户端的结果
        return json($result, $this->httpCode);
    }

    private function recordMessage(Exception $e){
        //对异常类进行初始化
        Log::init([
            'type' => 'File',
            'path' => __DIR__.'/../logs',
            'level' => ['error']
        ]);
        //记录异常
        Log::record($e->getMessage(),'error');
    }

}