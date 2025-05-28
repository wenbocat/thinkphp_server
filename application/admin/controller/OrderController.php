<?php
/**
 * Created by PhpStorm.
 * User: yun7_mac
 * Date: 2019-11-26
 * Time: 14:46
 */

namespace app\admin\controller;


use app\common\model\AdminLogModel;
use app\common\model\AgentModel;
use app\common\model\AgentProfitModel;
use app\common\model\OrderModel;
use app\common\model\UserModel;
use think\Db;
use think\facade\Cookie;
use think\facade\Request;

class OrderController extends BaseController
{
    public function index(){
        return $this->fetch();
    }

    public function edit(){
        $order = OrderModel::get(Request::param("id"));
        $this->assign("orderinfo",$order);
        return $this->fetch();
    }

    public function getlist(){
        $where = [];
        $whereTimeStart = '';
        $whereTimeEnd = '';

        $order_no = Request::param("order_no");
        $out_trade_no = Request::param("out_trade_no");
        $uid = Request::param('uid');
        $pay_status = Request::param('pay_status');
        $type = Request::param('type');
        $start_time = Request::param("start_time");
        $end_time = Request::param("end_time");

        if ($order_no){
            $where["order_no"] = $order_no;
        }
        if ($out_trade_no){
            $where["out_trade_no"] = $out_trade_no;
        }
        if ($uid){
            $where["uid"] = $uid;
        }
        if ($type != null){
            $where["type"] = $type;
        }
        if ($pay_status){
            $where["pay_status"] = $pay_status;
        }
        if ($start_time){
            $whereTimeStart = "create_time > '{$start_time}'";
        }
        if ($end_time){
            $whereTimeEnd = "create_time < '{$end_time}'";
        }
        $page = Request::param("page");
        $limit = Request::param("limit");
        $orders = OrderModel::where($where)->where($whereTimeStart)->where($whereTimeEnd)->with(['user' => function($query){
            $query->field('id, nick_name');
        }])->limit(($page-1)*$limit,$limit)->order("create_time","desc")->select();
        if (count($orders)>0){
            $count = OrderModel::where($where)->where($whereTimeStart)->where($whereTimeEnd)->count("id");
            return json(["code"=>0,"msg"=>"","data"=>$orders,"count"=>$count]);
        }
        return self::bulidFail("未查询到相关数据");
    }

    public function edit_post(){
        $order = OrderModel::where(['id'=>Request::param('id')])->find();
        if (!$order){
            return self::bulidFail("订单不存在");
        }
        $param = Request::param();
        $param["pay_status"] = 1;
        $param['pay_amount'] = $order->amount;

        Db::startTrans();

        if ($order->save($param)){
            $user = UserModel::where(['id'=>$order->uid])->find();
            $user->gold = ['inc',$order->gold+$order->gold_added];

            if ($order->type == 1 && $order->vip_level > 0){
                //订单类型：开通vip
                $user->vip_level = $order->vip_level;
                if ($user->vip_date && strtotime($user->vip_date)>time()){
                    $newtime = strtotime($user->vip_date) + 30*24*60*60;
                }else{
                    $newtime = time() + 30*24*60*60;
                }
                $user->vip_date = date('Y-m-d H:i:s',$newtime);
            }

            //代理返佣
            $agent = AgentModel::where(['uid'=>$user->agentid])->find();
            if ($agent){
                $config_pri = getConfigPri();
                $profit = round($config_pri->agent_ratio * $order->amount) * 0.01;
                $agent->profit = ['inc',$profit];
                $agent->total_profit = ['inc',$profit];
                $agentProfit = new AgentProfitModel(['agentid'=>$agent->uid,'profit'=>$profit,'content'=>"下级用户(ID:{$user->id})充值奖励",'create_time'=>nowFormat()]);
                Db::startTrans();
                if ($agent->save() && $agentProfit->save()){
                    Db::commit();
                }else{
                    Db::rollback();
                }
            }
            if ($user->save()){
                //写管理员操作日志
                $adminlog = new AdminLogModel(["adminid"=>$this->userinfo["id"],"content"=>$this->userinfo["name"]."将用户".$user->nick_name."(".$user->id.")"."的充值订单".$order->order_no."状态修改为支付成功", "create_time"=>date("Y-m-d H:i:s")]);
                $adminlog->save();
                Db::commit();
                return self::bulidSuccess([]);
            }else{
                Db::rollback();
                return self::bulidFail("用户加金币失败");
            }
        }else{
            return self::bulidFail();
        }
    }

    public function del_post(){
        if (OrderModel::destroy(Request::param('id'))){
            return self::bulidSuccess([]);
        }else{
            return self::bulidFail();
        }
    }
}