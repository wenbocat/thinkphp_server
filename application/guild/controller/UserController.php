<?php


namespace app\guild\controller;


use app\common\model\GuildMemberApplyModel;
use app\common\model\GuildModel;
use app\common\model\SystemMsgModel;
use app\common\model\UserModel;
use app\common\TXService;
use think\Db;
use think\facade\Request;

class UserController extends BaseController
{
    public function index(){
        return $this->fetch();
    }

    public function getlist(){
        $searchid = Request::param("id");
        $searchisonline = Request::param("online_status");
        $searchaccount = Request::param("account");
        $where['guildid'] = $this->userinfo->id;
        if ($searchid){
            $where["id"] = $searchid;
        }
        if ($searchisonline != ''){
            $where["online_status"] = $searchisonline;
        }
        if ($searchaccount != ''){
            $where["account"] = $searchaccount;
        }
        $page = Request::param("page");
        $limit = Request::param("limit");
        $userlist = UserModel::where($where)->with(['profile'])->limit(($page-1)*$limit,$limit)->order("regist_time","desc")->select();
        if (count($userlist)>0){
            $count = UserModel::where($where)->count("id");
            return json(["code"=>0,"msg"=>"","data"=>$userlist,"count"=>$count]);
        }
        return self::bulidFail("未查询到相关数据");
    }

    public function setSharingRatio_post(){
        $sharing_ratio = Request::param('sharing_ratio');
        if ($sharing_ratio > $this->userinfo->sharing_ratio){
            return self::bulidFail('分红比例超出最大值');
        }
        if (UserModel::where(['id'=>Request::param('id'),'guildid'=>$this->userinfo->id])->update(['sharing_ratio'=>$sharing_ratio])){
            return self::bulidSuccess();
        }
        return self::bulidFail();
    }

    public function del_post(){
        $id = Request::param('id');
        if (UserModel::where(['id'=>$id,'guildid'=>$this->userinfo->id])->update(['guildid'=>0]) ){
            if ($this->userinfo->member_count > 0){
                GuildModel::where(['id'=>$this->userinfo->id])->update(['member_count'=>['dec',1]]);
            }
            self::echoSuccess();
            $title = '公会合约已解除';
            $content = $this->userinfo->name."：您的公会合约已被解除";
            $stmmsgModel = new SystemMsgModel(['touid'=>$id, 'title'=>$title, 'content'=>$content, 'create_time'=>nowFormat()]);
            if ($stmmsgModel->save()){
                $elem = TXService::buildCustomElem('SystemMessage',[],'入会申请审核被拒绝');
                TXService::sendSystemMsg([strval($id)],$elem);
            }
            exit();
        }
        return self::bulidFail();
    }

    public function apply(){
        return $this->fetch();
    }

    public function getApplyList(){
        $id = Request::param("id");
        $status = Request::param("status");
        $account = Request::param("account");
        $where['guildid'] = $this->userinfo->id;
        if ($id){
            $where["id"] = $id;
        }
        if ($status != ''){
            $where["status"] = $status;
        }
        if ($account != ''){
            $where["account"] = $account;
        }

        $page = Request::param("page");
        $limit = Request::param("limit");
        $userlist = GuildMemberApplyModel::where($where)->with('user')->limit(($page-1)*$limit,$limit)->order("create_time","desc")->select();
        if (count($userlist)>0){
            $count = UserModel::where($where)->count("id");
            return json(["code"=>0,"msg"=>"","data"=>$userlist,"count"=>$count]);
        }
        return self::bulidFail("未查询到相关数据");
    }

    public function checkApply(){
        $status = Request::param('status');
        $apply = GuildMemberApplyModel::where(['id'=>Request::param('id'),'guildid'=>$this->userinfo->id])->find();
        if ($apply->status == $status || $apply->status != 0){
            return self::bulidFail('入会申请状态异常');
        }
        $user = UserModel::where(['id'=>$apply->uid])->find();
        if ($user->status != 0){
            return self::bulidFail('该用户账号状态异常');
        }

        $apply->status = $status;
        $apply->operate_time = nowFormat();
        if ($status == 1){
            if ($user->guildid > 0){
                return self::bulidFail('该用户已加入其他公会');
            }
            Db::startTrans();
            $user->guildid = $apply->guildid;
            $this->userinfo->member_count = ['inc',1];
            if ($user->save() && $apply->save() && $this->userinfo->save()){
                Db::commit();
                self::echoSuccess();
                $title = '入会申请审核通过';
                $content = $this->userinfo->name."：您的入会申请已审核通过";
                $stmmsgModel = new SystemMsgModel(['touid'=>$apply->uid, 'title'=>$title, 'content'=>$content, 'create_time'=>nowFormat()]);
                if ($stmmsgModel->save()){
                    $elem = TXService::buildCustomElem('SystemMessage',[],'入会申请已审核通过');
                    TXService::sendSystemMsg([strval($apply->uid)],$elem);
                }
                exit();
            }else{
                Db::rollback();
                return self::bulidFail();
            }
        }else{
            if ($apply->save()){
                self::echoSuccess();
                $title = '入会申请审核被拒绝';
                $content = $this->userinfo->name."：您的入会申请被拒绝";
                $stmmsgModel = new SystemMsgModel(['touid'=>$apply->uid, 'title'=>$title, 'content'=>$content, 'create_time'=>nowFormat()]);
                if ($stmmsgModel->save()){
                    $elem = TXService::buildCustomElem('SystemMessage',[],'入会申请审核被拒绝');
                    TXService::sendSystemMsg([strval($apply->uid)],$elem);
                }
                exit();
            }
            return self::bulidFail();
        }
    }

    public function signForCos()
    {
        return parent::signForCos();
    }
}