<?php


namespace app\admin\controller;


use app\common\model\SmscodeModel;
use think\facade\Request;

class SmscodeController extends BaseController
{
    public function index(){
        return $this->fetch();
    }

    public function getlist(){
        $mobile = Request::param("mobile");
        $where = [];
        if ($mobile){
            $where['mobile'] = $mobile;
        }
        $page = Request::param("page");
        $limit = Request::param("limit");
        $codes = SmscodeModel::where($where)->limit(($page-1)*$limit,$limit)->order('create_time', 'desc')->select();
        if (count($codes)>0){
            $count = SmscodeModel::where($where)->count();
            return json(["code"=>0,"msg"=>"","data"=>$codes,"count"=>$count]);
        }
        return self::bulidFail("未查询到相关数据");
    }
}