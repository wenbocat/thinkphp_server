<?php


namespace app\shop\controller;


use app\common\model\ShopDepositOrderModel;
use app\common\model\ShopGoodsModel;
use app\common\model\ShopModel;
use app\common\model\ShopOrderGoodsModel;
use app\common\model\ShopOrderReturnModel;
use app\common\model\ShopSuborderModel;
use app\common\model\ShopWithdrawalsModel;
use app\common\model\UserWithdrawAccountModel;
use think\Db;
use think\facade\Request;

class ShopController extends BaseController
{
    /**
     * 需要登录的方法
     */
    protected $NeedLogin = ['shopOrderList','walletInfo','withdraw','withdrawLog','getShopGoods','setGoodsSaleStatus'];

    /**
     * 参数校验规则
     */
    protected $rules = [
        'shoporderlist'=>[
            'type'=>'require',
        ],
        'getshopgoods'=>[
            'type'=>'require'  //0-待审核 1-已上架 2-已下架 3-审核被拒
        ],
        'stopsalegoods'=>[
            'goodsid'=>'require',
            'status'=>'require',    //1-上架 2-下架
        ],
    ];

    public function shopHomePageData(){
        $shopid = Request::param('shopid');
        $shop = ShopModel::where(['id'=>$shopid])->with('user')->find();

        $goods_count = ShopGoodsModel::where(['shopid'=>$shopid])->count('id');
        $shop->goods_count = $goods_count;

        $income_today = ShopSuborderModel::where(['shopid'=>$shopid])->where('status','in',[1,3])->whereTime('create_time','today')->sum('total_price');
        $shop->income_today = $income_today;

        $order_count_unpay = ShopSuborderModel::where(['shopid'=>$shopid,'status'=>0])->count('id');
        $order_count_undelivery = ShopSuborderModel::where(['shopid'=>$shopid,'status'=>1,'delivery_status'=>0])->count('id');
        $order_count_refund = ShopOrderReturnModel::where(['shopid'=>$shopid])->where('status','in',[0,1])->count('id');
        $shop->order_count_unpay = $order_count_unpay;
        $shop->order_count_undelivery = $order_count_undelivery;
        $shop->order_count_refund = $order_count_refund;

        return self::bulidSuccess($shop);
    }

    public function shopOrderList(){
        $type = Request::param('type'); //0-待付款 1-待发货 2-已发货 3-已完成 4-退款
        $page = Request::param('page') ?? 1;
        $size = Request::param('size') ?? 20;

        $list = [];
        switch ($type){
            case 0:
                $list = ShopSuborderModel::where(['shopid'=>$this->userinfo->id,'status'=>0])->order(['create_time'=>'desc'])->with('goods')->limit(($page-1)*$size,$size)->select();
                break;
            case 1:
                $list = ShopSuborderModel::where(['shopid'=>$this->userinfo->id,'status'=>1,'delivery_status'=>0])->order(['create_time'=>'desc'])->with('goods')->limit(($page-1)*$size,$size)->select();
                break;
            case 2:
                $list = ShopSuborderModel::where(['shopid'=>$this->userinfo->id,'delivery_status'=>1])->order(['create_time'=>'desc'])->with('goods')->limit(($page-1)*$size,$size)->select();
                break;
            case 3:
                $list = ShopSuborderModel::where(['shopid'=>$this->userinfo->id,'status'=>3])->order(['create_time'=>'desc'])->with('goods')->limit(($page-1)*$size,$size)->select();
                break;
            case 4:
                $list = ShopOrderReturnModel::where(['shopid'=>$this->userinfo->id])->order(['create_time'=>'desc'])->with('goods,suborder,shop')->limit(($page-1)*$size,$size)->select();
                break;
            default:
                break;
        }
        return self::bulidSuccess($list);
    }

    public function getShopGoods(){
        //0-待审核 1-已上架 2-已下架 3-审核被拒
        $type = Request::param('type');
        $page = Request::param('page') ?? 1;
        $size = Request::param('size') ?? 20;
        $list = ShopGoodsModel::where(['shopid'=>$this->userinfo->id,'status'=>$type])->order(['create_time'=>'desc'])->with('category,shop')->limit(($page-1)*$size,$size)->select();
        return self::bulidSuccess($list);
    }

    public function setGoodsSaleStatus(){
        $goodsid = Request::param('goodsid');
        $status = Request::param('status');
        if (ShopGoodsModel::where(['shopid'=>$this->userinfo->id,'id'=>$goodsid])->update(['status'=>$status])){
            return self::bulidSuccess();
        }
        return self::bulidFail();
    }

    public function walletInfo(){
        $shop = ShopModel::where(['id'=>$this->userinfo->id])->find();
        if (!$shop){
            return self::bulidFail();
        }
        $cash_account = UserWithdrawAccountModel::where(['uid'=>$this->userinfo->id])->order(['default'=>'desc','id'=>'desc'])->find();
        $config_pri = getConfigPri();
        return self::bulidSuccess(['cash_account'=>$cash_account,'commission'=>$config_pri->shop_commission,'profit'=>$shop->profit,'withdraw_min'=>$config_pri->withdraw_min]);
    }

    public function withdraw(){
        $shop = ShopModel::where(['id'=>$this->userinfo->id])->find();
        if (!$shop){
            return self::bulidFail();
        }
        $apply_cash = Request::param('apply_cash');
        if (floatval($apply_cash) > $shop->profit){
            return self::bulidFail('可提现余额不足');
        }
        $config_pri = getConfigPri();
        if ($config_pri->withdraw_min > $apply_cash){
            return self::bulidFail('最低提现金额'.$config_pri->withdraw_min.'元');
        }
        $commission_cash = round($apply_cash*$config_pri->shop_commission*0.01,2);
        $withdraw = new ShopWithdrawalsModel(Request::param());
        $withdraw->shopid = $shop->id;
        $withdraw->create_time = nowFormat();
        $withdraw->commission_cash = $commission_cash;
        $withdraw->trade_cash = $apply_cash - $commission_cash;

        $shop->profit = ['dec',$apply_cash];
        Db::startTrans();
        if ($shop->save() && $withdraw->save()){
            Db::commit();
            return self::bulidSuccess([],'工作人员将在24小时内处理您的提现申请');
        }else{
            Db::rollback();
            return self::bulidFail();
        }
    }

    public function withdrawLog(){
        $page = Request::post("page") ?? 1;
        $size = Request::post("size") ?? 20;
        $logs = ShopWithdrawalsModel::where(['shopid'=>$this->userinfo->id])->order('create_time','desc')->limit(($page-1)*$size,$size)->select();
        return self::bulidSuccess($logs);
    }

}