<?php
namespace app\guild\controller;

use app\common\model\UserModel;
use app\common\model\OrderModel;
use app\common\model\GuildModel;
use app\common\model\UserProfileModel;
use app\common\model\UserPhotoModel;
use app\common\model\AgentModel;
use app\common\TXService;
use think\Db;
use think\facade\Request;

class SalesmanController extends BaseController
{
    public function index(){
        return $this->fetch();
    }

    // 业务员列表
    public function getlist(){
        $searchid = Request::param("id");
        $searchisonline = Request::param("online_status");
        $searchaccount = Request::param("account");
        $page = Request::param("page");
        $limit = Request::param("limit");
        $where['guildid'] = $this->userinfo->id;
        $where['is_salesman'] = 1;
        if ($searchid){
            $where["id"] = $searchid;
        }
        if ($searchisonline != ''){
            $where["online_status"] = $searchisonline;
        }
        if ($searchaccount != ''){
            $where["account"] = $searchaccount;
        }
        $user_list = UserModel::where($where)->with(['profile'])->limit(($page-1)*$limit,$limit)->order("regist_time","desc")->select();
        if (count($user_list)>0){
            foreach ($user_list as $key => $val) {
                $user_list[$key]['invit_num'] = UserModel::where(['agentid'=>$val['id']])->count('id');
                $user_list[$key]['invite_code'] = AgentModel::where(['uid'=>$val['id']])->value('invite_code');
            }
            $count = UserModel::where($where)->count("id");
            return json(["code"=>0,"msg"=>"","data"=>$user_list,"count"=>$count]);
        }
        return self::bulidFail("未查询到相关数据");
    }

    // 添加业务员
    public function add(){
        return $this->fetch();
    }

    public function add_post(){
        $userModel = new UserModel(Request::param());
        $userProfileModel = new UserProfileModel(Request::param());
        //检测手机号是否占用
        $checkUser = UserModel::get(["account"=>$userModel->account]);
        if ($checkUser){
            return self::bulidFail("手机号已被占用");
        }
        $userModel->password = openssl_encrypt(substr($userModel->account,strlen($userModel->account)-4,4).'123456',"DES-ECB",$this->OPENSSL_KEY);
        $userModel->regist_time = date("Y-m-d H:i:s");
        $userModel->last_login = date("Y-m-d H:i:s");
        $userModel->is_salesman = 1;
        $userModel->guildid = $this->userinfo->id;
        Db::startTrans();
        if ($userModel->save()){
            //注册IM账号
            $importRes = TXService::importAccount($userModel->id,$userModel->nick_name,$userModel->avatar);
            if (!$importRes['ActionStatus'] == 'OK'){
                Db::rollback();
                return self::bulidSuccess('IM用户注册失败');
            }
            $invite_code = createInviteCode();
            $agent = new AgentModel(['uid'=>$userModel->id,'profit'=>0,'total_profit'=>0,'invite_code'=>$invite_code]);
            //保存封面图
            $photoModel = new UserPhotoModel();
            $photoModel->uid = $userModel->id;
            $photoModel->img_url = Request::param("cover_image");
            $photoModel->is_cover = 1;
            $photoModel->create_time = date("Y-m-d H:i:s");
            $userProfileModel->uid = $userModel->id;
            if ($userProfileModel->save() && $agent->save() && $photoModel->save()){
                Db::commit();
                return self::bulidSuccess($userModel,"添加用户成功");
            }else{
                TXService::deleteAccount($userModel->id);
                Db::rollback();
                return self::bulidFail();
            }
        }else{
            Db::rollback();
            return self::bulidFail();
        }
    }

    // 邀请页面
    public function invit(){
        // 获取业务员列表
        $user_list = UserModel::where(['is_salesman'=>1,'guildid'=>$this->userinfo->id])->select();
        $this->assign('user_list', $user_list);
        return $this->fetch();
    }

    // 获取邀请成员列表
    public function invitList(){
        $agent_id = Request::param("agent_id");
        $limit = Request::param("limit", 10);
        $where = [];
        if($agent_id > 0){
            $where['agentid'] = $agent_id;
        }else{
            $agent_ids = UserModel::where(['is_salesman'=>1,'guildid'=>$this->userinfo->id])->column('id');
            $where[] = ['agentid', 'in', $agent_ids];
        }
        $user_list = UserModel::where($where)
            ->where(['is_salesman'=>0])
            ->with(['profile'])
            ->paginate($limit, false, [
                    'query' => \request()->request()
                ]);
        if($user_list->items()){
            foreach ($user_list->items() as $key => $val) {
                $user_list[$key]['salesman_name'] = $val['nick_name'];
            }
            $result = array("code" => 0, "count" => $user_list->total(), "data" => $user_list->items());
            return json($result);    
        }
        return self::bulidFail('未查询到相关数据');
    }

    // 充值页面
    public function recharge(){
        // 获取业务员列表
        $user_list = UserModel::where(['is_salesman'=>1,'guildid'=>$this->userinfo->id])->select();
        $this->assign('user_list', $user_list);
        return $this->fetch();
    }

    // 获取充值列表
    public function rechargeList(){
        $order_no = Request::param("order_no");
        $agent_id = Request::param("agent_id");
        $limit = Request::param("limit", 10);
        $where = [];
        $order_no > 0 && $where['order.order_no'] = $order_no;
        if($agent_id > 0){
            $where['u.agentid'] = $agent_id;
        }else{
            $agent_ids = UserModel::where(['is_salesman'=>1,'guildid'=>$this->userinfo->id])->column('id');
            $where[] = ['u.agentid', 'in', $agent_ids];
        }
        $list = OrderModel::alias('order')
                ->leftJoin('user u','order.uid = u.id')
                ->where($where)
                ->paginate($limit, false, [
                    'query' => \request()->request()
                ]);
        if($list->items()){
            $result = array("code" => 0, "count" => $list->total(), "data" => $list->items());
            return json($result);    
        }
        return self::bulidFail('未查询到相关数据');
    }
}