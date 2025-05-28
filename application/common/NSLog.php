<?php


namespace app\common;


use think\facade\Env;

class NSLog
{
    static function writeRuntimeLog($file='logs',$json=[]){
        file_put_contents(Env::get('runtime_path') . $file . '_'.date('Y-m-d').'.txt',date('Y-m-d H:i:s').':'.json_encode($json)."\r\n",FILE_APPEND);
    }
}