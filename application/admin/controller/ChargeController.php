<?php
/**
 * Created by PhpStorm.
 * User: yun7_mac
 * Date: 2020-02-12
 * Time: 19:54
 */

namespace app\admin\controller;


use app\common\model\AdminLogModel;
use app\common\model\OrderModel;
use app\common\model\UserModel;
use think\Db;
use think\facade\Cookie;
use think\facade\Request;

class ChargeController extends BaseController
{
    public function manual(){
        return $this->fetch();
    }

    public function manual_post(){
        $id = Request::param('id');
        $type = Request::param('opt_type');
        $money = Request::param('money');
        $count = Request::param('count');
        $user = UserModel::where('id',$id)->field('id, nick_name, gold')->find();
        if (!$user){
            return self::bulidFail('用户不存在');
        }
        $order = null;
        if ($type == 1){
            $user->gold = ['inc',$count];
            $adminlog = new AdminLogModel(["adminid"=>Cookie::get("uid"),"content"=>$this->userinfo["name"]."给用户".$user->nick_name."(".$user->id.")"."充值金币".$count, "create_time"=>date("Y-m-d H:i:s")]);
            $order = new OrderModel(['uid'=>$id,'order_no'=>date('YmdHis').rand(100,999),'amount'=>$money,'pay_amount'=>$money,'gold'=>$count,'pay_channel'=>5,'create_time'=>nowFormat(),'pay_time'=>nowFormat(),'pay_status'=>1]);
        }elseif ($type == 0){
            $user->gold = ['dec',$count];
            $adminlog = new AdminLogModel(["adminid"=>Cookie::get("uid"),"content"=>$this->userinfo["name"]."扣除用户".$user->nick_name."(".$user->id.")"."金币".$count, "create_time"=>date("Y-m-d H:i:s")]);
        }else{
            return self::bulidFail();
        }

        Db::startTrans();
        if ($user->save() && $adminlog->save()){
            if ($order && !$order->save()){
                Db::rollback();
                return self::bulidFail();
            }
            Db::commit();
            return self::bulidSuccess();
        }
        Db::rollback();
        return self::bulidFail();
    }
}