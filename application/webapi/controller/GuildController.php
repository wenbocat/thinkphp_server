<?php


namespace app\webapi\controller;


use app\common\model\GuildMemberApplyModel;
use app\common\model\GuildModel;
use think\facade\Request;

class GuildController extends BaseController
{

    /**
     * 需要登录的方法
     */
    protected $NeedLogin = ['getUserGuild','getGuildInfo','applyJoinGuild','revokeApply'];

    /**
     * 参数校验规则
     */
    protected $rules = [
        'getguildinfo'=>[
            'guildid'=>'require',
        ],
        'applyjoinguild'=>[
            'guildid'=>'require',
        ],
    ];

    public function getGuildList(){
        if ($keyword = Request::param('keyword')){
            $list = GuildModel::whereRaw("status = 1 and (id = '{$keyword}' or name like '%{$keyword}%')")->order(['create_time'=>'desc'])->select();
        }else{
            $list = GuildModel::where(['status'=>1])->order(['create_time'=>'desc'])->select();
        }
        return self::bulidSuccess($list);
    }

    public function getUserGuild(){
        $guildid = 0;
        if ($this->userinfo->guildid){
            $guild = GuildModel::where(['id'=>$this->userinfo->guildid])->find();
            $guildid = $guild->id;
        }else{
            $apply = GuildMemberApplyModel::where(['uid'=>$this->userinfo->id,'status'=>0])->find();
            if ($apply) {
                $guild = GuildModel::where(['id' => $apply->guildid])->find();
                $guildid = $guild->id;
            }
        }
        return self::bulidSuccess(['guildid'=>$guildid]);
    }

    public function getGuildInfo(){
        $guildid = Request::param('guildid');
        $guild = GuildModel::where(['id'=>$guildid])->with('users')->find();
        $apply_status = -1;
        if ($this->userinfo->guildid == $guildid){
            $apply_status = 1;
        }else{
            $apply = GuildMemberApplyModel::where(['uid'=>$this->userinfo->id,'status'=>0])->find();
            if ($apply && $apply->guildid == $guildid) {
                $apply_status = 0;
            }
        }
        return self::bulidSuccess(['guild'=>$guild,'apply_status'=>$apply_status]);
    }

    public function applyJoinGuild(){
        if ($this->userinfo->guildid > 0){
            return self::bulidFail('你已加入其他公会');
        }
        $membership = GuildMemberApplyModel::where(['uid'=>$this->userinfo->id,'status'=>0])->find();
        if ($membership){
            return self::bulidFail('你的入会申请正在审核中，请勿重复申请');
        }

        $guildid = Request::param('guildid');
        $guild = GuildModel::where(['id'=>$guildid])->find();
        if ($guild->status != 1){
            return self::bulidFail('公会状态异常');
        }
        $membership = new GuildMemberApplyModel(['uid'=>$this->userinfo->id,'guildid'=>$guildid]);
        $membership->create_time = nowFormat();
        if ($membership->save()){
            return self::bulidSuccess('申请成功，请等待会长审核');
        }
        return self::bulidFail();
    }

    public function revokeApply(){
        if (GuildMemberApplyModel::where(['uid'=>$this->userinfo->id,'status'=>0])->delete()){
            return self::bulidSuccess();
        }
        return self::bulidFail();
    }
}