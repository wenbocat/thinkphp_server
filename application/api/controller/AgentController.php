<?php


namespace app\api\controller;


use app\common\model\AgentModel;
use app\common\model\AgentProfitModel;
use app\common\model\AgentWithdrawalsModel;
use app\common\model\UserModel;
use app\common\model\UserWithdrawAccountModel;
use think\Db;
use think\facade\Request;

class AgentController extends BaseController
{

    protected $NeedLogin = ['getAgentInfo','getInviteList','getProfitLog','withDraw','withdrawlog'];

    protected $rules = array(
        'withdraw'=>[
            'cash'=>'require',
            'alipay_account'=>'require',
            'alipay_name'=>'require'
        ]
    );

    public function getAgentInfo(){
        $agent = AgentModel::where(['uid'=>$this->userinfo->id])->find();
        $configPub = getConfigPub();
        $agent->invite_url = $configPub->dl_web_url."{$agent->invite_code}";
        return self::bulidSuccess($agent);
    }

    public function getInviteList(){
        $page = Request::param('page') ?? 1;
        $size = Request::param('size') ?? 20;
        $time_range = Request::param('time_range', 'all'); // month year
        if($time_range == 'all'){
            $list = UserModel::where(['agentid'=>$this->userinfo->id,'status'=>0])
                ->field(['id','nick_name','regist_time','avatar'])
                ->order('regist_time','desc')
                ->limit(($page-1)*$size,$size)->select();    
        }else{
            $list = UserModel::where(['agentid'=>$this->userinfo->id,'status'=>0])
                ->field(['id','nick_name','regist_time','avatar'])
                ->order('regist_time','desc')
                ->whereTime('regist_time', $time_range)
                ->limit(($page-1)*$size,$size)->select();  
        }
        $count_total = 0;
        $count_today = 0;
        if ($page == 1){
            $count_total = UserModel::where(['agentid'=>$this->userinfo->id,'status'=>0])->count('id');
            $count_today = UserModel::where(['agentid'=>$this->userinfo->id,'status'=>0])->whereTime('regist_time','today')->count('id');
        }
        return self::bulidSuccess(['list'=>$list,'count_total'=>$count_total,'count_today'=>$count_today]);
    }

    public function getProfitLog(){
        $page = Request::param('page') ?? 1;
        $size = Request::param('size') ?? 20;
        $list = AgentProfitModel::where(['agentid'=>$this->userinfo->id])->order(['create_time'=>'desc'])->limit(($page-1)*$size,$size)->select();
        return self::bulidSuccess($list);
    }

    public function withDraw(){
        $withdraw = new AgentWithdrawalsModel(Request::param());
        $withdraw->create_time = nowFormat();

        $agent = AgentModel::where(['uid'=>$this->userinfo->id])->find();
        if (!$agent){
            return self::bulidFail();
        }
        if (floatval($withdraw->cash) > $agent->profit){
            return self::bulidFail('可提现金额不足');
        }
        $agent->profit = ['dec',$withdraw->cash];
        Db::startTrans();
        if ($agent->save() && $withdraw->save()){
            Db::commit();
            $agent = AgentModel::where(['uid'=>$this->userinfo->id])->find();
            return self::bulidSuccess($agent,'工作人员将在24小时内处理您的提现申请');
        }else{
            Db::rollback();
            return self::bulidFail();
        }
    }

    public function withdrawlog(){
        $page = Request::post("page") ?? 1;
        $size = Request::post("size") ?? 20;
        $logs = AgentWithdrawalsModel::where(['uid'=>$this->userinfo->id])->order('create_time','desc')->limit(($page-1)*$size,$size)->select();
        return self::bulidSuccess($logs);
    }
}