<?php


namespace app\webapi\controller;


use Alipay\EasySDK\Kernel\Factory;
use Alipay\EasySDK\Kernel\Util\ResponseChecker;
use app\common\model\OrderModel;
use app\common\model\VipPriceModel;
use think\facade\Env;
use think\facade\Request;

class VipController extends BaseController
{
    protected $NeedLogin = ['getAliPayOrder'];

    protected $rules = [];

    public function initialize()
    {
        parent::initialize();
    }

    public function getVipPriceList(){
        $list = VipPriceModel::select();
        return self::bulidSuccess($list);
    }

    public function getAliPayOrder(){
        $item = VipPriceModel::where(['level'=>Request::param('level')])->find();
        if (!$item){
            return self::bulidFail('信息错误');
        }
        $config_pri = getConfigPri();
        //配置参数检测
        if($config_pri->alipay_appid == "" || $config_pri->alipay_prikey == "" || $config_pri->alipay_pubkey == ""){
            return self::bulidFail('暂不支持支付宝');
        }
        $order_no = self::createOrderNo($this->userinfo->id);
        $order = new OrderModel(['uid'=>$this->userinfo->id,'vip_level'=>$item->level,'type'=>1,'order_no'=>$order_no,'amount'=>$item->price,'gold'=>$item->gold,'pay_channel'=>1,'create_time'=>nowFormat()]);
        if ($order->save()) {
            Factory::setOptions(self::getOptions());
            $result = Factory::payment()->app()->pay("开通贵族-VIP{$item->level}",$order_no,strval($item->price));
            file_put_contents(Env::get('runtime_path')."AliPay_".date('Y-m-d').".txt",date('y-m-d H:i:s').' 下单返回信息 ch:'.json_encode($result)."\r\n",FILE_APPEND);
            $responseChecker = new ResponseChecker();
            //处理响应或异常
            if ($responseChecker->success($result)) {
                return self::bulidSuccess(['paystr'=>$result->body]);
            } else {
                return self::bulidDataFail($result);
            }
        }
        return self::bulidFail();
    }
}