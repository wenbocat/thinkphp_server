<?php


namespace app\webapi\controller;

use Alipay\EasySDK\Kernel\Factory;
use Alipay\EasySDK\Kernel\Util\ResponseChecker;
use app\common\model\GoldPriceModel;
use app\common\model\OrderModel;
use think\facade\Env;
use think\facade\Request;

class RechargeController extends BaseController
{
    protected $NeedLogin = ['getWxPayOrder', 'getAliPayOrder', 'getOrderPayStatus'];

    protected $rules = array(
        'getwxpayorder'=>[
            'itemid'=>'require'
        ],
        'getalipayorder'=>[
            'itemid'=>'require'
        ]
    );

    public function initialize()
    {
        parent::initialize();
    }

    public function getPriceList(){
        $list = GoldPriceModel::order('gold','asc')->select();
        return self::bulidSuccess($list);
    }

    public function getWxPayOrder(){
        $item = GoldPriceModel::where(['id'=>Request::param('itemid')])->find();
        if (!$item){
            return self::bulidFail('信息错误');
        }
        $config_pri = getConfigPri();
        //配置参数检测
        if($config_pri->wx_appid == "" || $config_pri->wx_mchid == "" || $config_pri->wx_key == ""){
            return self::bulidFail('暂不支持微信支付');
        }
        $order_no = self::createOrderNo($this->userinfo->id);
        $order = new OrderModel(['uid'=>$this->userinfo->id,'order_no'=>$order_no,'amount'=>$item->price,'gold'=>$item->gold,'gold_added'=>$item->gold_added,'pay_channel'=>1,'create_time'=>nowFormat()]);
        if ($order->save()){
            $noceStr = md5(rand(100,1000).time());//获取随机字符串
            $paramarr = array(
                "appid"       => $config_pri->wx_appid,
                "mch_id"      => $config_pri->wx_mchid,
                "nonce_str" => $noceStr,
                "body"        => "充值{$item->gold}虚拟币",
                "out_trade_no" => $order_no,
                "total_fee"   => $item->price * 100,
                "spbill_create_ip" => \request()->ip(),
                "notify_url"  => $config_pri->pay_notify_domain.'/api/paynotify/notify_wxpay',
                "trade_type"  => "NATIVE"
            );
            $sign = self::makeSign($paramarr, $config_pri->wx_key);//生成签名
            $paramarr['sign'] = $sign;
            $paramXml = "<xml>";
            foreach($paramarr as $k => $v){
                $paramXml .= "<" . $k . ">" . $v . "</" . $k . ">";
            }
            $paramXml .= "</xml>";
            $ch = curl_init ();
            @curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
            @curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true);  // 从证书中检查SSL加密算法是否存在
            @curl_setopt($ch, CURLOPT_URL, "https://api.mch.weixin.qq.com/pay/unifiedorder");
            @curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            @curl_setopt($ch, CURLOPT_POST, 1);
            @curl_setopt($ch, CURLOPT_POSTFIELDS, $paramXml);
            @$resultXmlStr = curl_exec($ch);
            if(curl_errno($ch)){
                file_put_contents(Env::get('runtime_path')."WXPay_".date('Y-m-d').".txt",date('y-m-d H:i:s').' 提交参数信息 ch:'.json_encode(curl_error($ch))."\r\n",FILE_APPEND);
            }
            curl_close($ch);
            $wx_res = self::xmlToArray($resultXmlStr);
            file_put_contents(Env::get('runtime_path')."WXPayRet_".date('Y-m-d').".txt",date('y-m-d H:i:s').' 返回信息:'.json_encode($wx_res)."\r\n",FILE_APPEND);
            if($wx_res['return_code']=='FAIL' && $wx_res['result_code']=='FAIL'){
                return self::bulidFail($wx_res['return_msg']);
            }
            $noceStr = md5(rand(100,1000).time());//获取随机字符串
            $paramarr2 = [
                'order_no' => $order_no,
                'code_url' => $wx_res['code_url'],
                'prepay_id' => $wx_res['prepay_id']
            ];
            return self::bulidSuccess($paramarr2);
        }
        return self::bulidFail();
    }

    public function getAliPayOrder(){
        $item = GoldPriceModel::where(['id'=>Request::param('itemid')])->find();
        if (!$item){
            return self::bulidFail('信息错误');
        }
        $config_pri = getConfigPri();
        //配置参数检测
        if($config_pri->alipay_appid == "" || $config_pri->alipay_prikey == "" || $config_pri->alipay_pubkey == ""){
            return self::bulidFail('暂不支持支付宝');
        }
        $order_no = self::createOrderNo($this->userinfo->id);
        $order = new OrderModel(['uid'=>$this->userinfo->id,'order_no'=>$order_no,'amount'=>$item->price,'gold'=>$item->gold,'gold_added'=>$item->gold_added,'pay_channel'=>1,'create_time'=>nowFormat()]);
        if ($order->save()) {
            Factory::setOptions(self::getOptions());
            $result = Factory::payment()->app()->pay("充值{$item->gold}虚拟币",$order_no,strval($item->price));
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

    // 订单支付状态
    public function getOrderPayStatus(){
        $order_no = Request::post('order_no');
        $uid = $this->userinfo->id;
        $pay_status = OrderModel::where(['uid'=>$uid,'order_no'=>$order_no])->value('pay_status') ?: 0;
        return self::bulidSuccess(['pay_status'=>$pay_status]);
    }
}