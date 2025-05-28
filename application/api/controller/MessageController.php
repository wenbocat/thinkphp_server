<?php
/**
 * Created by PhpStorm.
 * User: yun7_mac
 * Date: 2020-01-19
 * Time: 14:27
 */

namespace app\api\controller;


use app\common\model\SystemMsgModel;
use think\facade\Request;

class MessageController extends BaseController
{
    protected $NeedLogin = ['getSystemMsg'];

    protected $rules = array(
        'getsystemmsg'=>[],
    );

    public function getSystemMsg(){
        $lastid = Request::param('lastid');
        $where = "(touid = {$this->userinfo->id} or touid = 0)";
        if ($lastid){
            $where .= 'and id < '.$lastid;
        }
        $size = Request::param('size');
        if (!$size){
            $size = 20;
        }
        $msg = SystemMsgModel::where($where)->limit(0,$size)->order('id','desc')->select();
        return self::bulidSuccess($msg);
    }
}