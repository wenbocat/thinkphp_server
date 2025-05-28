<?php
/**
 * Created by PhpStorm.
 * User: yun7_mac
 * Date: 2020-02-12
 * Time: 19:33
 */

namespace app\admin\controller;

use app\common\model\AgentModel;
use app\common\model\AgentWithdrawalsModel;
use app\common\model\UserModel;
use think\Db;
use think\facade\Request;

class AgentController extends BaseController
{
    public function index(){
        return $this->fetch();
    }

    public function add(){
        return $this->fetch();
    }

    public function withdrawals(){
        return $this->fetch();
    }

    public function withdrawals_edit(){
        $id = Request::param('id');
        $withdrawalsinfo = AgentWithdrawalsModel::get($id);
        $this->assign("withdrawalsinfo",$withdrawalsinfo);
        return $this->fetch();
    }

    public function getlist(){
        $where = [];
        if ($id = Request::param('id')){
            $where['uid'] = $id;
        }
        $page = Request::param("page");
        $limit = Request::param("limit");
        $list = AgentModel::where($where)->with('user')->limit(($page-1)*$limit,$limit)->select();
        if (count($list)>0){

            foreach ($list as $agent){
                $member_count = UserModel::where("agentid",$agent->uid)->count();
                $agent->member_count = $member_count;
            }

            $count = AgentModel::where($where)->count();
            return json(["code"=>0,"msg"=>"","data"=>$list,"count"=>$count]);
        }
        return self::bulidFail("未查询到相关数据");
    }

    public function getWithdrawList(){
        $id = Request::param('id');
        $uid = Request::param('uid');
        $trade_no = Request::param('trade_no');
        $status = Request::param('status');

        $where = [];
        if ($id){
            $where['id'] = $id;
        }
        if ($uid){
            $where['uid'] = $uid;
        }
        if ($trade_no){
            $where['trade_no'] = $trade_no;
        }
        if ($status != null){
            $where['status'] = $status;
        }

        $page = Request::param("page");
        $limit = Request::param("limit");

        $orders = AgentWithdrawalsModel::where($where)->with('agent')->limit(($page-1)*$limit,$limit)->order("create_time","desc")->select();
        if (count($orders)>0){
            $count = AgentWithdrawalsModel::where($where)->count("id");
            return json(["code"=>0,"msg"=>"","data"=>$orders,"count"=>$count]);
        }
        return self::bulidFail("未查询到相关数据");
    }

    public function withdrawals_edit_post(){
        $id = Request::param('id');
        $withdrawalsinfo = AgentWithdrawalsModel::get($id);
        if (!$withdrawalsinfo || $withdrawalsinfo->status == 1){
            return self::bulidFail("提现申请不存在或已处理");
        }
        $trade_no = Request::param('trade_no');
        if ($withdrawalsinfo->save(['trade_no'=>$trade_no,'status'=>1,'operate_time'=>nowFormat()])){
            return self::bulidSuccess([]);
        }
        return self::bulidFail("处理失败");
    }

    public function withdrawals_edit_refuse(){
        $id = Request::param('id');
        $withdrawalsinfo = AgentWithdrawalsModel::get($id);
        if (!$withdrawalsinfo || $withdrawalsinfo->status == 1){
            return self::bulidFail("提现申请不存在或已处理");
        }
        Db::startTrans();
        if ($withdrawalsinfo->save(['status'=>2,'operate_time'=>nowFormat()])){
            //归还余额
            $agent = AgentModel::where(['uid'=>$withdrawalsinfo->uid])->find();
            $agent->profit = ['inc',$withdrawalsinfo->cash];
            if ($agent->save()){
                Db::commit();
                return self::bulidSuccess([]);
            }else{
                Db::rollback();
                return self::bulidFail("处理失败");
            }
            return self::bulidSuccess([]);
        }
        return self::bulidFail("处理失败");
    }

    public function withdrawals_edit_abnormal(){
        $id = Request::param('id');
        $withdrawalsinfo = AgentWithdrawalsModel::get($id);
        if (!$withdrawalsinfo || $withdrawalsinfo->status == 1){
            return self::bulidSuccess([]);
        }
        if ($withdrawalsinfo->save(['status'=>3,'operate_time'=>nowFormat()])){
            return self::bulidSuccess([]);
        }
        return self::bulidFail("处理失败");
    }
}