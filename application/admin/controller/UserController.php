<?php
/**
 * Created by PhpStorm.
 * User: yun7_mac
 * Date: 2019-11-19
 * Time: 14:46
 */

namespace app\admin\controller;


use app\common\model\AdminLogModel;
use app\common\model\agentModel;
use app\common\model\AnchorReportModel;
use app\common\model\UserModel;
use app\common\model\UserPhotoModel;
use app\common\model\UserProfileModel;
use app\common\TXService;
use think\Db;
use think\facade\Cookie;
use think\facade\Request;

class UserController extends BaseController
{
    public function index(){
        return $this->fetch();
    }

    public function add(){
        return $this->fetch();
    }

    public function edit(){
        $id = Request::param("id");
        $userModel = UserModel::get($id);
        $this->assign("userinfo",$userModel);
        return $this->fetch();
    }

    public function report(){
        return $this->fetch();
    }

    public function signForCos()
    {
        return parent::signForCos();
    }

    public function getlist(){
        $searchid = Request::param("id");
        $searchname = Request::param("nick_name");
        $searchisanchor = Request::param("is_anchor");
        $searchisonline = Request::param("online_status");
        $searchvip = Request::param('vip_level');
        $searchaccount = Request::param("account");
        $where = [];
        if ($searchid){
            $where["id"] = $searchid;
        }
        if ($searchname){
            $where["nick_name"] = $searchname;
        }
        if ($searchisanchor != ''){
            $where["is_anchor"] = $searchisanchor;
        }
        if ($searchisonline != ''){
            $where["online_status"] = $searchisonline;
        }
        if ($searchvip != ''){
            $where["vip_level"] = $searchvip;
        }
        if ($searchaccount != ''){
            $where["account"] = $searchaccount;
        }
        $page = Request::param("page");
        $limit = Request::param("limit");
        $userlist = UserModel::where($where)->with(['profile'])->limit(($page-1)*$limit,$limit)->order("regist_time","desc")->select();
        if ($this->userinfo->role->is_visitor){
            //访客 隐藏手机号
            foreach ($userlist as $user){
                $user->account = '访客模式不可查看';
            }
        }
        if (count($userlist)>0){
            $count = UserModel::where($where)->count("id");
            return json(["code"=>0,"msg"=>"","data"=>$userlist,"count"=>$count]);
        }
        return self::bulidFail("未查询到相关数据");
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

    public function edit_post(){
        $id = Request::param("id");
        $sharing_ratio = Request::param("sharing_ratio");
        $pwd = Request::param("password");

        $userModel = UserModel::get($id);
        if (!$userModel){
            return self::bulidFail("用户不存在");
        }
        Db::startTrans();

        $userModel->sharing_ratio = $sharing_ratio;

        if ($pwd){
            $userModel->password = openssl_encrypt(substr($userModel->account,strlen($userModel->account)-4,4).$pwd,"DES-ECB",$this->OPENSSL_KEY);;
        }

        if ($userModel->save()){
            Db::commit();
            return self::bulidSuccess([],"编辑成功");
        }else{
            Db::rollback();
            return self::bulidFail();
        }
    }

    //封号/解封
    public function del_post(){
        $userModel = UserModel::get(Request::param("id"));

        if (!$userModel){
            return self::bulidFail("参数错误");
        }

        $userModel->status = Request::param("status");

        if ($userModel->save()){
            return self::bulidSuccess([],"操作成功");
        }else{
            return self::bulidFail();
        }
    }

    public function getReportList(){
        $page = Request::param("page");
        $limit = Request::param("limit");
        $res = AnchorReportModel::with(['user','anchor'])->order('create_time','desc')->limit(($page-1)*$limit,$limit)->select();
        if (count($res)>0){
            $count = AnchorReportModel::count('id');
            return json(["code"=>0,"msg"=>"","data"=>$res,"count"=>$count]);
        }
        return self::bulidFail("未查询到相关数据");
    }
}