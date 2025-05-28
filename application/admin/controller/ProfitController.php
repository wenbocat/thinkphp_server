<?php
/**
 * Created by PhpStorm.
 * User: yun7_mac
 * Date: 2020-01-04
 * Time: 00:05
 */

namespace app\admin\controller;


use app\common\model\UserProfitModel;
use think\facade\Request;

class ProfitController extends BaseController
{
    public function index(){
        return $this->fetch();
    }

    public function getProfitList(){
        $uid = Request::param('uid');
        $type = Request::param('type');
        $where = [];
        if ($uid){
            $where['uid'] = $uid;
        }
        if ($type != ''){
            $where['type'] = $type;
        }
        $page = Request::param("page");
        $limit = Request::param("limit");
        $list = UserProfitModel::where($where)->with(['user'])->limit(($page-1)*$limit,$limit)->order("create_time","desc")->select();
        if (count($list)>0){
            $count = UserProfitModel::where($where)->count("id");
            return json(["code"=>0,"msg"=>"","data"=>$list,"count"=>$count]);
        }
        return self::bulidFail("未查询到相关数据");
    }
}