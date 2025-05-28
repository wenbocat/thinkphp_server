<?php


namespace app\shop\controller;


use Alipay\EasySDK\Kernel\Factory;
use Alipay\EasySDK\Kernel\Util\ResponseChecker;
use app\common\model\ShopGoodsInventoryModel;
use app\common\model\ShopGoodsModel;
use app\common\model\ShopModel;
use app\common\model\ShopOrderGoodsModel;
use app\common\model\ShopOrderModel;
use app\common\model\ShopOrderReturnModel;
use app\common\model\ShopSuborderModel;
use app\common\model\UserAddressModel;
use app\common\NSLog;
use think\Db;
use think\facade\Env;
use think\facade\Request;

class OrderController extends BaseController
{
    /**
     * 需要登录的方法
     */
    protected $NeedLogin = ['submitOrder','deliveryOrder','confirmReceipt','getOrderInfo','applyReturnGoods','operateReturn','cancelOrder','returnOrderInfo','getExpressInfo','shopReceiveReturn','submitReturnExpress'];

    /**
     * 参数校验规则
     */
    protected $rules = [
        'submitorder'=>[
            'addressid'=>'require',
            'total_price'=>'require',
            'lists'=>'require',
        ],
        'deliveryorder'=>[
            'suborderid'=>'require',
            'express_no'=>'require',
            'express_company'=>'require',
        ],
        'confirmreceipt'=>[
            'suborderid'=>'require',
        ],
        'getorderinfo'=>[
            'suborderid'=>'require',
        ],
        'applyreturngoods'=>[
            'ordergoodsid'=>'require',
            'reason'=>'require',
            'amount'=>'require',
        ],
        'operatereturn'=>[
            'returnid'=>'require',
            'operate'=>'require',   //1-接受  2-拒绝
        ],
        'cancelorder'=>[
            'suborderid'=>'require',
        ],
        'returnorderinfo'=>[
            'ordergoodsid'=>'require',
        ],
        'getexpressinfo'=>[
            'suborderid'=>'require',
        ],
        'shopreceivereturn'=>[
            'returnid'=>'require',
        ],
        'submitreturnexpress'=>[
            'returnid'=>'require',
            'express_no'=>'require',
        ],
    ];

    public function getOrderInfo(){
        $suborderid = Request::param('suborderid');
        $order = ShopSuborderModel::where(['id'=>$suborderid])->with('shop,user')->find();
        $goods = ShopOrderGoodsModel::where(['suborderid'=>$suborderid])->with('goods')->select();
        foreach ($goods as $goods_item){
            $returnModel = ShopOrderReturnModel::where(['ordergoodsid'=>$goods_item->id])->find();
            if ($returnModel){
                $goods_item->return_status = $returnModel->status;
            }else{
                $goods_item->return_status = 0;
            }
        }
        return self::bulidSuccess(['order'=>$order,'goods'=>$goods]);
    }

    public function cancelOrder(){
        $suborderid = Request::param('suborderid');
        $order = ShopSuborderModel::where(['id'=>$suborderid,'uid'=>$this->userinfo->id])->with('shop')->find();
        if (!$order || $order->status != 0){
            return self::bulidFail('订单状态异常');
        }
        Db::startTrans();
        $order->status = 2;
        if (!$order->save()){
            Db::rollback();
            return self::bulidFail();
        }
        $goods = ShopOrderGoodsModel::where(['suborderid'=>$suborderid])->select();
        foreach ($goods as $goods_item){
            //返还库存
            $inventory = ShopGoodsInventoryModel::where(['id'=>$goods_item->inventoryid])->find();
            if ($inventory){
                $inventory->left_count = ['inc',$goods_item->count];
                $inventory->sale_count = ['dec',$goods_item->count];
                if (!$inventory->save()){
                    Db::rollback();
                    return self::bulidFail();
                }
            }
        }
        Db::commit();
        return self::bulidSuccess();
    }

    public function submitOrder(){
        //NSLog::writeRuntimeLog('submitorder',Request::param());
        $address = UserAddressModel::where(['id'=>Request::param('addressid'),'uid'=>$this->userinfo->id])->find();
        if (!$address){
            return self::bulidFail('收货地址信息有误');
        }

        //创建母订单
        $order_no = self::createOrderNo($this->userinfo->id,'NS');
        $order = new ShopOrderModel();
        $order->uid = $this->userinfo->id;
        $order->order_no = $order_no;
        $order->total_price = Request::param('total_price');
        $order->create_time = nowFormat();

        Db::startTrans();
        if (!$order->save()){
            Db::rollback();
            return self::bulidFail();
        }

        $lists = Request::param("lists");
        $total_price = 0;
        $inventoryids = []; //库存id数组 用于下单成功后自动删除购物车中数据
        foreach ($lists as $list){
            //创建子订单
            $subOrder_no = self::createOrderNo($this->userinfo->id);
            $subOrder = new ShopSuborderModel();
            $subOrder->uid = $this->userinfo->id;
            $subOrder->shopid = $list['shopid'];
            $subOrder->parentid = $order->id;
            $subOrder->order_no = $subOrder_no;
            $subOrder->total_price = $list['total_price'];
            $subOrder->remark = $list['remark'];
            $subOrder->create_time = nowFormat();
            $subOrder->receiver_name = $address->name;
            $subOrder->receiver_mobile = $address->mobile;
            $subOrder->receiver_address = $address->province.$address->city.$address->district.$address->address;
            if (!$subOrder->save()){
                Db::rollback();
                return self::bulidFail();
            }

            $goods_total_price = 0;
            $goodsArr = $list['goods'];
            foreach ($goodsArr as $goods){
                $inventoryids[] = $goods['inventoryid'];
                //创建订单关联商品
                $goodsModel = ShopGoodsModel::where(['id'=>$goods['id']])->find();
                if (!$goodsModel || $goodsModel->shopid != $list['shopid']){
                    Db::rollback();
                    return self::bulidFail('商品信息有误');
                }
                if ($goodsModel->status != 1){
                    Db::rollback();
                    return self::bulidFail('商品已下架');
                }

                $inventory = ShopGoodsInventoryModel::where(['id'=>$goods['inventoryid']])->with(['color','size'])->find();
                if (!$inventory){
                    Db::rollback();
                    return self::bulidFail('商品规格信息有误');
                }
                if ($inventory->left_count <= 0){
                    Db::rollback();
                    return self::bulidFail("商品已售罄");
                }
                if ($inventory->price != $goods['price']){
                    Db::rollback();
                    return self::bulidFail("数据异常");
                }

                //写入订单商品表
                $order_goods = new ShopOrderGoodsModel();
                $order_goods->uid = $this->userinfo->id;
                $order_goods->shopid = $list['shopid'];
                $order_goods->suborderid = $subOrder->id;
                $order_goods->inventoryid = $inventory->id;
                $order_goods->goodsid = $goods['id'];
                $order_goods->color = $inventory->color->color;
                if ($inventory->size) {
                    $order_goods->size = $inventory->size->size;
                }
                $order_goods->count = $goods['count'];
                $order_goods->price = $inventory->price;
                if (!$order_goods->save()){
                    Db::rollback();
                    return self::bulidFail();
                }

                //减库存
                if(ShopGoodsInventoryModel::where('left_count','>',$goods['count'])->where(['id'=>$goods['inventoryid']])->update(['left_count'=>['dec',$goods['count']],'sale_count'=>['inc',$goods['count']]]) <= 0){
                    Db::rollback();
                    return self::bulidFail('商品库存不足');
                }
                //加销量
                ShopGoodsModel::where(['id'=>$goods['id']])->update(['sale_count'=>['inc',$goods['count']]]);

                $goods_total_price += $inventory->price * $goods['count'];
            }
            if ($goods_total_price != floatval($list['total_price'])){
                Db::rollback();
                return self::bulidFail("数据异常");
            }

            $total_price += $list['total_price'];
        }
        if (floatval($total_price) != floatval($order->total_price)){
            Db::rollback();
            return self::bulidFail("数据异常");
        }
        Db::commit();
        CartController::autoDelCartGoods($inventoryids,$this->userinfo->id);
        return self::bulidSuccess(['order_no'=>$order->order_no,'total_price'=>$order->total_price]);
    }

    public function deliveryOrder(){
        $order = ShopSuborderModel::where(['id'=>Request::param('suborderid')])->find();
        if ($order->shopid != $this->userinfo->id){
            return self::bulidFail('订单信息有误');
        }
        if ($order->status != 1){
            return self::bulidFail('订单未支付');
        }
        if ($order->delivery_status != 0){
            return self::bulidFail('该订单已发货完成');
        }
        $order->express_no = Request::param('express_no');
        $order->express_company = Request::param('express_company');
        $order->delivery_status = 1;
        if ($order->save()){
            return self::bulidSuccess();
        }
        return self::bulidFail();
    }

    public function confirmReceipt(){
        $order = ShopSuborderModel::where(['id'=>Request::param('suborderid')])->find();
        if ($order->uid != $this->userinfo->id){
            return self::bulidFail('订单信息有误');
        }
        if ($order->status != 1){
            return self::bulidFail('订单未支付');
        }
        if ($order->delivery_status == 0){
            return self::bulidFail('该订单尚未发货');
        }
        Db::startTrans();
        //处理订单状态
        $order->status = 3;
        //处理商户资金
        $shop = ShopModel::where(['id'=>$order->shopid])->find();
        $shop->profit = ['inc',$order->total_price - $order->return_amount];
        $shop->total_profit = ['inc',$order->total_price - $order->return_amount];
        if (ShopSuborderModel::update(['status'=>3],['id'=>Request::param('suborderid'),'status'=>1]) && $shop->save()){
            Db::commit();
            return self::bulidSuccess();
        }
        Db::rollback();
        return self::bulidFail();
    }

    public function applyReturnGoods(){
        $ordergoodsid = Request::param('ordergoodsid');
        $ordergoods = ShopOrderGoodsModel::where(['uid'=>$this->userinfo->id,'id'=>$ordergoodsid])->find();
        if (!$ordergoods){
            return self::bulidFail('数据异常');
        }

        $returnModel = ShopOrderReturnModel::where(['ordergoodsid'=>$ordergoodsid])->find();
        
        if ($returnModel && $returnModel->return_status != 3 && $returnModel->return_status != 5){
            return self::bulidFail('请勿重复申请');
        }
        if ($returnModel && ($returnModel->return_status == 3 || $returnModel->return_status == 5)){
            $returnModel->status = 0;
        }else{
            $returnModel = new ShopOrderReturnModel(['uid'=>$this->userinfo->id,'shopid'=>$ordergoods->shopid,'suborderid'=>$ordergoods->suborderid,'ordergoodsid'=>$ordergoodsid,'reason'=>Request::param('reason'),'desc'=>Request::param('desc'),'amount'=>Request::param('amount'),'create_time'=>nowFormat()]);
        }
        if ($returnModel->save()){
            return self::bulidSuccess(['id'=>$returnModel->id]);
        }else{
            return self::bulidFail();
        }
    }

    public function operateReturn(){
        $returnModel = ShopOrderReturnModel::where(['id'=>Request::param('returnid'),'shopid'=>$this->userinfo->id])->find();
        if (!$returnModel || $returnModel->status != 1){
            return self::bulidFail('退货申请状态异常');
        }
        $operate = Request::param('operate');
        $returnModel->operate_time = nowFormat();
        if ($operate == 1){
            if (Request::param('need_return') == 1){
                $returnModel->status = 2;
                //需要退回货物
                $address = UserAddressModel::where(['uid'=>$this->userinfo->id,'id'=>Request::param('addressid')])->find();
                if (!$address){
                    return self::bulidFail('退货地址有误');
                }
                $returnModel->receiver_name = $address->name;
                $returnModel->receiver_mobile = $address->mobile;
                $returnModel->receiver_address = $address->province.$address->city.$address->district.$address->address;
                if ($returnModel->save()){
                    return self::bulidSuccess();
                }else
                    Db::rollback();
            }elseif (Request::param('need_return') == 2){
                //不需要退回货物 直接退款
                Db::startTrans();
                $suborder = ShopSuborderModel::where(['id'=>$returnModel->suborderid,'shopid'=>$this->userinfo->id])->find();
                if (!$suborder){
                    Db::rollback();
                    return self::bulidFail('参数有误');
                }
                if ($suborder->total_price - $suborder->return_amount < $returnModel->amount){
                    Db::rollback();
                    return self::bulidFail('退款金额超出订单总金额');
                }
                $suborder->return_amount = ['inc',$returnModel->amount];
                $returnModel->status = 4;
                if ($returnModel->save() && $suborder->save()){
                    $pay_order_no = $suborder->order_no;
                    $pay_no = $suborder->pay_no;
                    $order_pay_amount = $suborder->pay_amount;
                    if ($suborder->pay_type == 1){
                        //通过母订单进行支付的
                        $order = ShopOrderModel::where(['id'=>$suborder->parentid,'pay_status'=>1])->find();
                        if (!$order){
                            Db::rollback();
                            return self::bulidFail("订单异常");
                        }
                        $pay_order_no = $order->order_no;
                        $order_pay_amount = $order->pay_amount;
                        $pay_no = $order->pay_no;
                    }
                    if ($suborder->pay_channel == 1){
                        if ($refund_trade_no = PayController::wxRefund($pay_no,$returnModel->amount,$order_pay_amount,$returnModel->id)){
                            ShopOrderReturnModel::update(['refund_trade_no'=>$refund_trade_no],['id'=>$returnModel->id]);
                            Db::commit();
                            return self::bulidSuccess();
                        }else{
                            Db::rollback();
                            return self::bulidFail("退款失败");
                        }
                    }elseif ($suborder->pay_channel == 2){
                        if($refund_trade_no = PayController::alipayRefund($pay_order_no,$returnModel->amount)){
                            ShopOrderReturnModel::update(['refund_trade_no'=>$refund_trade_no],['id'=>$returnModel->id]);
                            Db::commit();
                            return self::bulidSuccess();
                        }else{
                            Db::rollback();
                            return self::bulidFail("退款失败");
                        }
                    }
                    Db::rollback();
                    return self::bulidFail("订单支付渠道异常");
                }else
                    Db::rollback();
            }else{
                Db::rollback();
                return self::bulidFail('参数有误');
            }
        }else{
            $returnModel->status = 3;
            if ($returnModel->save()){
                return self::bulidSuccess();
            }
        }
        return self::bulidFail();
    }

    public function shopReceiveReturn(){
        $returnModel = ShopOrderReturnModel::where(['id'=>Request::param('returnid'),'shopid'=>$this->userinfo->id])->find();
        if (!$returnModel || $returnModel->status != 2){
            return self::bulidFail('退货申请状态异常');
        }
        $returnModel->operate_time = nowFormat();
        $returnModel->status = 4;
        Db::startTrans();
        $suborder = ShopSuborderModel::where(['id'=>$returnModel->suborderid,'shopid'=>$this->userinfo->id])->find();
        if (!$suborder){
            Db::rollback();
            return self::bulidFail('参数有误');
        }
        if ($suborder->total_price - $suborder->return_amount < $returnModel->amount){
            Db::rollback();
            return self::bulidFail('退款金额超出订单总金额');
        }
        $suborder->return_amount = ['inc',$returnModel->amount];
        if ($returnModel->save() && $suborder->save()){
            $order = ShopOrderModel::where(['id'=>$suborder->parentid,'pay_status'=>1])->find();
            $pay_order_no = $suborder->order_no;
            if ($suborder->pay_type == 1){
                //通过母订单进行支付的
                $order = ShopOrderModel::where(['id'=>$suborder->parentid,'pay_status'=>1])->find();
                if (!$order){
                    Db::rollback();
                    return self::bulidFail("订单异常");
                }
                $pay_order_no = $order->order_no;
            }
            if ($suborder->pay_channel == 1){
                if ($refund_trade_no = PayController::wxRefund($order->pay_no,$returnModel->amount,$order->pay_amount,$returnModel->id)){
                    ShopOrderReturnModel::update(['refund_trade_no'=>$refund_trade_no],['id'=>$returnModel->id]);
                    Db::commit();
                    return self::bulidSuccess();
                }else{
                    Db::rollback();
                    return self::bulidFail("退款失败");
                }
            }elseif ($suborder->pay_channel == 2){
                if ($refund_trade_no = PayController::alipayRefund($pay_order_no,$returnModel->amount)){
                    ShopOrderReturnModel::update(['refund_trade_no'=>$refund_trade_no],['id'=>$returnModel->id]);
                    Db::commit();
                    return self::bulidSuccess();
                }else{
                    Db::rollback();
                    return self::bulidFail("退款失败");
                }
            }
            Db::rollback();
            return self::bulidFail("订单支付渠道异常");
        }
        Db::rollback();
        return self::bulidFail();
    }

    public function returnOrderInfo(){
        $ordergoodsid = Request::param('ordergoodsid');
        $ordergoods = ShopOrderGoodsModel::where(['id'=>$ordergoodsid])->with('shop,suborder,goods,user')->find();
        if (!$ordergoods){
            return self::bulidFail('订单不存在');
        }
        if ($ordergoods->uid != $this->userinfo->id && $ordergoods->shopid != $this->userinfo->id){
            return self::bulidFail('订单归属异常');
        }
        $returnModel = ShopOrderReturnModel::where(['ordergoodsid'=>$ordergoodsid])->find();
        return self::bulidSuccess(['ordergoods'=>$ordergoods,'return_info'=>$returnModel]);
    }

    public function submitReturnExpress(){
        $returnModel = ShopOrderReturnModel::where(['id'=>Request::param('returnid'),'uid'=>$this->userinfo->id])->find();
        if (!$returnModel || $returnModel->status != 2){
            return self::bulidFail('退货申请状态异常');
        }
        $returnModel->express_no = Request::param('express_no');
        if ($returnModel->save()){
            return self::bulidSuccess();
        }
        return self::bulidFail();
    }

    public function getExpressInfo(){
        $orderid = Request::param('suborderid');
        $order = ShopSuborderModel::where(['id'=>$orderid,'uid'=>$this->userinfo->id])->find();
        if (!$order || !$order->express_no){
            return self::bulidFail('查询失败');
        }
        $company_code = self::$ExpressCompanyCodes[$order->express_company];
        $configPri = getConfigPri();
        $param = array (
            'com' => $company_code,             //快递公司编码
            'num' => $order->express_no,     //快递单号
            'phone' => '',                //手机号
            'from' => '',                 //出发地城市
            'to' => '',                   //目的地城市
            'resultv2' => '1'             //开启行政区域解析
        );

        //请求参数
        $post_data = array();
        $post_data["customer"] = $configPri->kuaidi100_customer;
        $post_data["param"] = json_encode($param);
        $sign = md5($post_data["param"].$configPri->kuaidi100_key.$post_data["customer"]);
        $post_data["sign"] = strtoupper($sign);

        $url = 'http://poll.kuaidi100.com/poll/query.do';    //实时查询请求地址

        $params = "";
        foreach ($post_data as $k=>$v) {
            $params .= "$k=".urlencode($v)."&";              //默认UTF-8编码格式
        }
        $post_data = substr($params, 0, -1);
        return self::bulidSuccess(json_decode(httpHelper($url,$post_data),true));
    }

    private static $ExpressCompanyCodes = ["圆通速递"=>"yuantong",
                                            "韵达快递"=>"yunda",
                                            "中通快递"=>"zhongtong",
                                            "顺丰速运"=>"shunfeng",
                                            "邮政快递包裹"=>"youzhengguonei",
                                            "百世快递"=>"huitongkuaidi",
                                            "京东物流"=>"jd",
                                            "申通快递"=>"shentong",
                                            "天天快递"=>"tiantian",
                                            "EMS"=>"ems",
                                            "邮政标准快递"=>"youzhengbk",
                                            "德邦"=>"debangwuliu",
                                            "德邦快递"=>"debangkuaidi",
                                            "韵达快运"=>"yundakuaiyun",
                                            "百世快运"=>"baishiwuliu",
                                            "中通快运"=>"zhongtongkuaiyun",
                                            "圆通快运"=>"yuantongkuaiyun"];
}