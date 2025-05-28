<?php


namespace app\admin\controller;


use app\common\model\ShopGoodsCategoryModel;
use app\common\model\ShopGoodsModel;
use app\common\model\ShopModel;
use app\common\model\ShopSuborderModel;
use app\common\model\ShopWithdrawalsModel;
use think\Db;
use think\facade\Request;

class ShopController extends BaseController
{
    public function shop()
    {
        return $this->fetch();
    }

    public function getShopList(){
        $page = Request::param('page') ?? 1;
        $limit = Request::param("limit") ?? 20;

        $where = [];

        $id = Request::param("id");
        $status = Request::param("status");

        if ($id){
            $where["id"] = $id;
        }
        if ($status != ''){
            $where['status'] = $status;
        }

        $lists = ShopModel::where($where)->with(['user'])->limit(($page-1)*$limit,$limit)->order('create_time', 'desc')->select();
        if (count($lists)>0){
            $count = ShopModel::where($where)->count('id');
            return json(["code"=>0,"msg"=>"","data"=>$lists,"count"=>$count]);
        }
        return self::bulidFail("未查询到相关数据");
    }

    public function close_post(){
        if (ShopModel::where(['id'=>Request::param('id')])->update(['status'=>3])){
            return self::bulidSuccess();
        }
        return self::bulidFail();
    }

    public function open_post(){
        if (ShopModel::where(['id'=>Request::param('id')])->update(['status'=>1])){
            return self::bulidSuccess();
        }
        return self::bulidFail();
    }


    /* —————————————————————————————————————————————————————————————————————————————————————————————————————————————————————————————————————————————— */


    public function goods(){
        $categorys = ShopGoodsCategoryModel::select();
        $this->assign('categorys',$categorys);
        return $this->fetch();
    }

    public function getGoodsList(){
        $page = Request::param('page') ?? 1;
        $limit = Request::param("limit") ?? 20;

        $where = [];

        $id = Request::param("id");
        $status = Request::param("status");
        $categoryid = Request::param("categoryid");
        $shopid = Request::param("shopid");

        if ($id){
            $where["id"] = $id;
        }
        if ($categoryid){
            $where["categoryid"] = $categoryid;
        }
        if ($shopid){
            $where["shopid"] = $shopid;
        }
        if ($status != ''){
            $where['status'] = $status;
        }

        $lists = ShopGoodsModel::where($where)->with(['shop','category'])->limit(($page-1)*$limit,$limit)->order('create_time', 'desc')->select();
        if (count($lists)>0){
            $count = ShopGoodsModel::where($where)->count('id');
            return json(["code"=>0,"msg"=>"","data"=>$lists,"count"=>$count]);
        }
        return self::bulidFail("未查询到相关数据");
    }

    public function setGoodsStatus_post(){
        $id = Request::param('id');
        $status = Request::param('status');
        if (ShopGoodsModel::where(['id'=>$id])->update(['status'=>$status])){
            return self::bulidSuccess();
        }
        return self::bulidFail();
    }

    /* ————————————————————————————————————————————————————— 订单管理 —————————————————————————————————————————————————————— */

    public function order(){
        return $this->fetch();
    }

    public function getOrderList(){
        $where = [];
        $whereTimeStart = '';
        $whereTimeEnd = '';

        $order_no = Request::param("order_no");
        $out_trade_no = Request::param("out_trade_no");
        $uid = Request::param('uid');
        $shopid = Request::param('shopid');
        $status = Request::param('status');
        $delivery_status = Request::param('delivery_status');
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
        if ($shopid){
            $where["shopid"] = $shopid;
        }
        if ($status){
            $where["status"] = $status;
        }
        if ($delivery_status){
            $where["delivery_status"] = $delivery_status;
        }
        if ($start_time){
            $whereTimeStart = "create_time > '{$start_time}'";
        }
        if ($end_time){
            $whereTimeEnd = "create_time < '{$end_time}'";
        }
        $page = Request::param("page");
        $limit = Request::param("limit");
        $orders = ShopSuborderModel::where($where)->where($whereTimeStart)->where($whereTimeEnd)->with(['goods','shop','user','parent'])->limit(($page-1)*$limit,$limit)->order("create_time","desc")->select();
        if (count($orders)>0){
            $count = ShopSuborderModel::where($where)->where($whereTimeStart)->where($whereTimeEnd)->count("id");
            return json(["code"=>0,"msg"=>"","data"=>$orders,"count"=>$count]);
        }
        return self::bulidFail("未查询到相关数据");
    }

    public function order_edit(){
        $order = ShopSuborderModel::get(Request::param("id"));
        $this->assign("orderinfo",$order);
        return $this->fetch();
    }

    public function order_edit_post(){
        //子订单支付
        $order_no = Request::param('order_no');
        $channel = Request::param('pay_channel');
        $pay_amount = Request::param('pay_amount');
        $suborder = ShopSuborderModel::where(['order_no'=>$order_no])->find();
        if (!$suborder){
            return self::bulidFail();
        }
        if ($suborder->status != 0 && $suborder->status != 2){
            return self::bulidSuccess();
        }
        $suborder->pay_time = nowFormat();
        $suborder->pay_channel = $channel;
        $suborder->pay_no = Request::param('out_trade_no');
        $suborder->pay_amount = $pay_amount;
        $suborder->status = 1;
        $suborder->pay_type = 2;
        if ($suborder->save()){
            return self::bulidSuccess();
        }
        return self::bulidFail();
    }

    /* ————————————————————————————————————————————————————— 商家提现 —————————————————————————————————————————————————————— */


    public function withdrawals(){
        return $this->fetch();
    }

    public function withdrawals_edit(){
        $id = Request::param('id');
        $withdrawalsinfo = ShopWithdrawalsModel::get($id);
        $this->assign("withdrawalsinfo",$withdrawalsinfo);
        return $this->fetch();
    }

    public function getWithdrawList(){
        $id = Request::param('id');
        $shopid = Request::param('shopid');
        $trade_no = Request::param('trade_no');
        $status = Request::param('status');

        $where = [];
        if ($id){
            $where['id'] = $id;
        }
        if ($shopid){
            $where['shopid'] = $shopid;
        }
        if ($trade_no){
            $where['trade_no'] = $trade_no;
        }
        if ($status != null){
            $where['status'] = $status;
        }

        $page = Request::param("page");
        $limit = Request::param("limit");

        $orders = ShopWithdrawalsModel::where($where)->with('shop')->limit(($page-1)*$limit,$limit)->order("create_time","desc")->select();
        if (count($orders)>0){
            $count = ShopWithdrawalsModel::where($where)->count("id");
            return json(["code"=>0,"msg"=>"","data"=>$orders,"count"=>$count]);
        }
        return self::bulidFail("未查询到相关数据");
    }

    public function withdrawals_edit_post(){
        $id = Request::param('id');
        $withdrawalsinfo = ShopWithdrawalsModel::get($id);
        if (!$withdrawalsinfo || $withdrawalsinfo->status == 1){
            return self::bulidFail("提现申请不存在或已处理");
        }
        $trade_no = Request::param('trade_no');
        if ($withdrawalsinfo->save(['trade_no'=>$trade_no,'status'=>1,'operate_time'=>nowFormat()])){
            return self::bulidSuccess([]);
        }
        return self::bulidFail("处理失败");
    }

    public function withdrawals_edit_refuse(){
        $id = Request::param('id');
        $withdrawalsinfo = ShopWithdrawalsModel::get($id);
        if (!$withdrawalsinfo || $withdrawalsinfo->status == 1){
            return self::bulidFail("提现申请不存在或已处理");
        }
        Db::startTrans();
        if ($withdrawalsinfo->save(['status'=>2,'operate_time'=>nowFormat()])){
            //归还余额
            $shop = ShopModel::where(['id'=>$withdrawalsinfo->shopid])->find();
            $shop->profit = ['inc',$withdrawalsinfo->apply_cash];
            if ($shop->save()){
                Db::commit();
                return self::bulidSuccess([]);
            }else{
                Db::rollback();
                return self::bulidFail("处理失败");
            }
            return self::bulidSuccess([]);
        }
        return self::bulidFail("处理失败");
    }

    public function withdrawals_edit_abnormal(){
        $id = Request::param('id');
        $withdrawalsinfo = ShopWithdrawalsModel::get($id);
        if (!$withdrawalsinfo || $withdrawalsinfo->status == 1){
            return self::bulidSuccess([]);
        }
        if ($withdrawalsinfo->save(['status'=>3,'operate_time'=>nowFormat()])){
            return self::bulidSuccess([]);
        }
        return self::bulidFail("处理失败");
    }
}