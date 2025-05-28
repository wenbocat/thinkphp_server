<?php


namespace app\admin\controller;


use app\common\model\ProtocalModel;
use think\facade\Request;

class ProtocalController extends BaseController
{
    public function index(){
        return $this->fetch();
    }

    public function edit(){
        $protocal = ProtocalModel::where(['id'=>Request::param('id')])->find();
        $this->assign('protocal',$protocal);
        return $this->fetch();
    }

    public function getlist(){
        $list = ProtocalModel::select();
        return self::bulidSuccess($list);
    }

    public function edit_post(){
        $protocal = ProtocalModel::where(['id'=>Request::param('id')])->find();
        $protocal->content = Request::param('content');
        if ($protocal->save()){
            return self::bulidSuccess();
        }
        return self::bulidFail();
    }
}