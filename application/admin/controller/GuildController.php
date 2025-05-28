<?php
/**
 * Created by PhpStorm.
 * User: yun7_mac
 * Date: 2019-12-11
 * Time: 15:11
 */

namespace app\admin\controller;


use app\common\model\GuildModel;
use app\common\model\GuildWithdrawalsModel;
use app\common\model\UserModel;
use think\Db;
use think\facade\Request;

class GuildController extends BaseController
{
    public function index(){
        return $this->fetch();
    }

    public function add(){
        return $this->fetch();
    }

    public function edit(){
        $guild = GuildModel::where(['id'=>Request::param('id')])->find();
        $this->assign('guild',$guild);
        return $this->fetch();
    }

    public function signForCos()
    {
        return parent::signForCos();
    }

    public function getlist(){
        $where = [];
        $where2 = '';
        if ($id = Request::param('id')){
            $where['id'] = $id;
        }
        if ($mobile = Request::param('mobile')){
            $where['mobile'] = $mobile;
        }
        if ($name = Request::param('name')){
            $where2 = "name like '%".$name."%'";
        }
        $page = Request::param("page");
        $limit = Request::param("limit");
        $list = GuildModel::where($where)->where($where2)->limit(($page-1)*$limit,$limit)->order("create_time","desc")->select();
        if (count($list)>0){

            foreach ($list as $guild){
                $member_count = UserModel::where("guildid",$guild->id)->count();
                $guild->member_count = $member_count;
            }

            $count = GuildModel::where($where)->where($where2)->count();
            return json(["code"=>0,"msg"=>"","data"=>$list,"count"=>$count]);
        }
        return self::bulidFail("未查询到相关数据");
    }

    public function add_post(){
        $guild = new GuildModel(Request::param());

        //检测手机号是否占用
        $check = GuildModel::get(["mobile"=>$guild->mobile]);
        if ($check){
            return self::bulidFail("手机号已被占用");
        }

        $guild->password = openssl_encrypt(substr($guild->mobile,strlen($guild->mobile)-4,4).'123456',"DES-ECB",$this->OPENSSL_KEY);
        $guild->create_time = date("Y-m-d H:i:s");

        if ($guild->save()){
            return self::bulidSuccess();
        }else{
            return self::bulidFail();
        }
    }

    public function edit_post(){
        $guild = GuildModel::where(['id'=>Request::param('id')])->find();

        //检测手机号是否占用
        $checkModel = GuildModel::get(["mobile"=>Request::param('mobile')]);
        if ($checkModel && $checkModel->id != $guild->id){
            return self::bulidFail("手机号已被占用");
        }elseif (!$checkModel){
            $guild->mobile = Request::param('mobile');
            $guild->password = openssl_encrypt(substr(Request::param('mobile'),strlen(Request::param('mobile'))-4,4).'123456',"DES-ECB",$this->OPENSSL_KEY);
        }
        $guild->sharing_ratio = Request::param("sharing_ratio");
        $guild->name = Request::param("name");
        $guild->icon = Request::param("icon");
        $guild->content = Request::param("content");

        if ($guild->save()){
            return self::bulidSuccess();
        }else{
            return self::bulidFail();
        }
    }

    public function ban_post(){
        if (GuildModel::where(['id'=>Request::param('id')])->update(['status'=>Request::param('status')])){
            return self::bulidSuccess();
        }
        return self::bulidFail();
    }

    public function withdrawals(){
        return $this->fetch();
    }

    public function withdraw_edit(){
        $id = Request::param('id');
        $withdrawalsinfo = GuildWithdrawalsModel::get($id);
        $this->assign("withdrawalsinfo",$withdrawalsinfo);
        return $this->fetch();
    }

    public function getWithdrawList(){
        $id = Request::param('id');
        $guildid = Request::param('guildid');
        $trade_no = Request::param('trade_no');
        $status = Request::param('status');

        $where = [];
        if ($id){
            $where['id'] = $id;
        }
        if ($guildid){
            $where['guild'] = $guildid;
        }
        if ($trade_no){
            $where['trade_no'] = $trade_no;
        }
        if ($status != null){
            $where['status'] = $status;
        }

        $page = Request::param("page");
        $limit = Request::param("limit");

        $orders = GuildWithdrawalsModel::where($where)->with('guild')->limit(($page-1)*$limit,$limit)->order("create_time","desc")->select();
        if (count($orders)>0){
            $count = GuildWithdrawalsModel::where($where)->count("id");
            return json(["code"=>0,"msg"=>"","data"=>$orders,"count"=>$count]);
        }
        return self::bulidFail("未查询到相关数据");
    }

    public function withdraw_edit_post(){
        $id = Request::param('id');
        $withdrawalsinfo = GuildWithdrawalsModel::get($id);
        if (!$withdrawalsinfo || $withdrawalsinfo->status == 1){
            return self::bulidFail("提现申请不存在或已处理");
        }
        $trade_no = Request::param('trade_no');
        if ($withdrawalsinfo->save(['trade_no'=>$trade_no,'status'=>1,'operate_time'=>nowFormat()])){
            return self::bulidSuccess([]);
        }
        return self::bulidFail("处理失败");
    }

    public function withdraw_edit_refuse(){
        $id = Request::param('id');
        $withdrawalsinfo = GuildWithdrawalsModel::get($id);
        if (!$withdrawalsinfo || $withdrawalsinfo->status == 1){
            return self::bulidFail("提现申请不存在或已处理");
        }
        Db::startTrans();
        if ($withdrawalsinfo->save(['status'=>2,'operate_time'=>nowFormat()])){
            //归还钻石
            if ($withdrawalsinfo->type == 1){
                //主播提现，返还钻石至公会账户
                $anchor = GuildModel::get($withdrawalsinfo->guildid);
                $anchor->diamond = ['inc',$withdrawalsinfo->diamond];
                if ($anchor->save()){
                    Db::commit();
                    return self::bulidSuccess([]);
                }else{
                    Db::rollback();
                    return self::bulidFail("处理失败");
                }
            }
            return self::bulidSuccess([]);
        }
        return self::bulidFail("处理失败");
    }

    public function edit_abnormal(){
        $id = Request::param('id');
        $withdrawalsinfo = GuildWithdrawalsModel::get($id);
        if (!$withdrawalsinfo || $withdrawalsinfo->status == 1){
            return self::bulidSuccess([]);
        }
        if ($withdrawalsinfo->save(['status'=>3,'operate_time'=>nowFormat()])){
            return self::bulidSuccess([]);
        }
        return self::bulidFail("处理失败");
    }
}