<?php


namespace app\guild\controller;


use app\common\model\GuildProfitModel;
use app\common\model\GuildWithdrawalsModel;
use think\Db;
use think\facade\Request;

class FinanceController extends BaseController
{
    public function profit(){
        return $this->fetch();
    }

    public function getprofitlist(){
        $page = Request::param("page");
        $limit = Request::param("limit");
        $list = GuildProfitModel::where(['guildid'=>$this->userinfo->id])->with(['guild'])->limit(($page-1)*$limit,$limit)->order("create_time","desc")->select();
        if (count($list)>0){
            $count = GuildProfitModel::where(['guildid'=>$this->userinfo->id])->count("id");
            return json(["code"=>0,"msg"=>"","data"=>$list,"count"=>$count]);
        }
        return self::bulidFail("未查询到相关数据");
    }

    public function withdrawlogs(){
        return $this->fetch();
    }

    public function getWithdrawList(){
        $page = Request::param("page");
        $limit = Request::param("limit");

        $orders = GuildWithdrawalsModel::where(['guildid'=>$this->userinfo->id])->with('guild')->limit(($page-1)*$limit,$limit)->order("create_time","desc")->select();
        if (count($orders)>0){
            $count = GuildWithdrawalsModel::where(['guildid'=>$this->userinfo->id])->count("id");
            return json(["code"=>0,"msg"=>"","data"=>$orders,"count"=>$count]);
        }
        return self::bulidFail("未查询到相关数据");
    }

    public function withdraw(){
        $this->assign('guild',$this->userinfo);
        $config_pri = getConfigPri();
        $this->assign('exchange_rate',$config_pri->exchange_rate);
        return $this->fetch();
    }

    public function withdraw_post(){
        $config_pri = getConfigPri();
        $diamond = intval(Request::param('diamond'));
        if ($this->userinfo->diamond < $diamond){
            return self::bulidFail('钻石余额不足');
        }
        $cash = floatval(Request::param('cash'));
        if ($cash > $diamond*$config_pri->exchange_rate/100 + 1){
            return self::bulidFail('数据有误');
        }
        $withdraw = new GuildWithdrawalsModel(Request::param());
        $withdraw->guildid = $this->userinfo->id;
        $withdraw->create_time = nowFormat();

        $this->userinfo->diamond = ['dec',$diamond];

        Db::startTrans();
        if ($withdraw->save() && $this->userinfo->save()){
            Db::commit();
            return self::bulidSuccess();
        }
        Db::rollback();
        return self::bulidFail();
    }
}