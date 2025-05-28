<?php


namespace app\webapi\controller;


use app\common\model\ProtocalModel;
use think\facade\Request;

class ProtocalController extends BaseController
{
    public function getProtocal(){
        $type = Request::param('type');
        $protocal = ProtocalModel::where(['type'=>$type])->find();
        return self::bulidSuccess($protocal);
    }
}