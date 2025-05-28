<?php
/**
 * Created by PhpStorm.
 * User: yun7_mac
 * Date: 2019-11-30
 * Time: 23:13
 */

namespace app\admin\controller;


use app\common\model\UserModel;
use app\common\model\AnchorWithdrawalsModel;
use think\Db;
use think\facade\Request;

class WithdrawalsController extends BaseController
{
    public function index(){
        return $this->fetch();
    }

    public function edit(){
        $id = Request::param('id');
        $withdrawalsinfo = AnchorWithdrawalsModel::get($id);
        $this->assign("withdrawalsinfo",$withdrawalsinfo);
        return $this->fetch();
    }

    public function getlist(){
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

        $orders = AnchorWithdrawalsModel::where($where)->with('anchor')->limit(($page-1)*$limit,$limit)->order("create_time","desc")->select();
        if (count($orders)>0){
            $count = AnchorWithdrawalsModel::where($where)->count("id");
            return json(["code"=>0,"msg"=>"","data"=>$orders,"count"=>$count]);
        }
        return self::bulidFail("未查询到相关数据");
    }

    public function edit_post(){
        $id = Request::param('id');
        $withdrawalsinfo = AnchorWithdrawalsModel::get($id);
        if (!$withdrawalsinfo || $withdrawalsinfo->status == 1){
            return self::bulidFail("提现申请不存在或已处理");
        }
        $trade_no = Request::param('trade_no');
        if ($withdrawalsinfo->save(['trade_no'=>$trade_no,'status'=>1,'operate_time'=>nowFormat()])){
            return self::bulidSuccess([]);
        }
        return self::bulidFail("处理失败");
    }

    public function edit_refuse(){
        $id = Request::param('id');
        $withdrawalsinfo = AnchorWithdrawalsModel::get($id);
        if (!$withdrawalsinfo || $withdrawalsinfo->status == 1){
            return self::bulidFail("提现申请不存在或已处理");
        }
        Db::startTrans();
        if ($withdrawalsinfo->save(['status'=>2,'operate_time'=>nowFormat()])){
            //归还钻石
            //主播提现，返还钻石至主播账户
            $anchor = UserModel::get($withdrawalsinfo->uid);
            $anchor->diamond = ['inc',$withdrawalsinfo->diamond];
            if ($anchor->save()){
                Db::commit();
                return self::bulidSuccess([]);
            }else{
                Db::rollback();
                return self::bulidFail("处理失败");
            }
        }
        return self::bulidFail("处理失败");
    }

    public function edit_abnormal(){
        $id = Request::param('id');
        $withdrawalsinfo = AnchorWithdrawalsModel::get($id);
        if (!$withdrawalsinfo || $withdrawalsinfo->status == 1){
            return self::bulidSuccess([]);
        }
        if ($withdrawalsinfo->save(['status'=>3,'operate_time'=>nowFormat()])){
            return self::bulidSuccess([]);
        }
        return self::bulidFail("处理失败");
    }

}