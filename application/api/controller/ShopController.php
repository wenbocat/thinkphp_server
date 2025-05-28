<?php


namespace app\api\controller;


use Alipay\EasySDK\Kernel\Factory;
use Alipay\EasySDK\Kernel\Util\ResponseChecker;
use app\common\model\ShopDepositOrderModel;
use app\common\model\ShopOrderModel;
use app\common\model\ShopSuborderModel;
use think\facade\Env;
use think\facade\Request;

class ShopController extends BaseController
{
    /**
     * 需要登录的方法
     */
    protected $NeedLogin = ['payDeposit'];

    /**
     * 参数校验规则
     */
    protected $rules = [
        //支付保证金
        'paydeposit'=>[
            'pay_channel'=>'require',
        ],
    ];

    public function payDeposit(){
        $order_no = self::createOrderNo($this->userinfo->id,'DP');
        $order = new ShopDepositOrderModel(['shopid'=>$this->userinfo->id,'order_no'=>$order_no,'pay_channel'=>Request::param('pay_channel'),'amount'=>1000,'create_time'=>nowFormat()]);
        if ($order->save()){
            if ($order->pay_channel == 1){
                return self::createWxPayOrder($order_no,1000,'DP');
            }elseif ($order->pay_channel == 2){
                return self::createAliPayOrder($order_no,1000,'DP');
            }
        }
        return self::bulidFail();
    }

    public function getWxPayOrder(){
        $order_no = Request::param('order_no');
        $total_fee = Request::param('total_fee');

        //检测订单是否存在
        if (strpos($order_no,'NS') === 0){
            //母订单
            $order = ShopOrderModel::where(['order_no'=>$order_no])->find();
            if (!$order){
                return self::bulidFail('订单不存在');
            }
            if ($order->pay_status != 0 && $order->pay_status != 2){
                return self::bulidFail('订单已支付');
            }
        }else{
            //子订单
            $suborder = ShopSuborderModel::where(['order_no'=>$order_no])->find();
            if (!$suborder){
                return self::bulidFail('订单不存在');
            }
            if ($suborder->status != 0 && $suborder->status != 2){
                return self::bulidFail('订单已支付');
            }
        }

        return self::createWxPayOrder($order_no,$total_fee);

    }

    public function getAliPayOrder(){
        $order_no = Request::param('order_no');
        $total_fee = Request::param('total_fee');

        //检测订单是否存在
        if (strpos($order_no,'NS') === 0){
            //母订单
            $order = ShopOrderModel::where(['order_no'=>$order_no])->find();
            if (!$order){
                return self::bulidFail('订单不存在');
            }
            if ($order->pay_status != 0 && $order->pay_status != 2){
                return self::bulidFail('订单已支付');
            }
        }else{
            //子订单
            $suborder = ShopSuborderModel::where(['order_no'=>$order_no])->find();
            if (!$suborder){
                return self::bulidFail('订单不存在');
            }
            if ($suborder->status != 0 && $suborder->status != 2){
                return self::bulidFail('订单已支付');
            }
        }
        return self::createAliPayOrder($order_no,$total_fee);
    }

    private static function createWxPayOrder($order_no,$total_fee,$attach='1'){
        $config_pri = getConfigPri();
        //配置参数检测
        if($config_pri->wx_appid == "" || $config_pri->wx_mchid == "" || $config_pri->wx_key == ""){
            return self::bulidFail('暂不支持微信支付');
        }
        $noceStr = md5(rand(100,1000).time());//获取随机字符串
        $paramarr = array(
            "appid"       =>    $config_pri->wx_appid,
            "attach"      =>    $attach,
            "body"        =>    "商户订单".$order_no,
            "mch_id"      =>    $config_pri->wx_mchid,
            "nonce_str"   =>    $noceStr,
            "notify_url"  =>    $config_pri->pay_notify_domain.'/shop/pay/notify_wxpay',
            "out_trade_no"=>    $order_no,
            "total_fee"   =>    $total_fee*100,
            "trade_type"  =>    "APP"
        );
        $sign = self::wxsign($paramarr,$config_pri->wx_key);//生成签名
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
            //print curl_error($ch);
            file_put_contents(Env::get('runtime_path')."WXPay_Shop_".date('Y-m-d').".txt",date('y-m-d H:i:s').' 提交参数信息 ch:'.json_encode(curl_error($ch))."\r\n",FILE_APPEND);
        }
        curl_close($ch);

        $wx_res = self::xmlToArray($resultXmlStr);

        if($wx_res['return_code']=='FAIL'){
            return self::bulidFail($wx_res['return_msg']);
        }
        $prepayid = $wx_res['prepay_id'];
        $noceStr = md5(rand(100,1000).time());//获取随机字符串
        $paramarr2 = array(
            "appid"     =>  $config_pri->wx_appid,
            "noncestr"  =>  $noceStr,
            "package"   =>  "Sign=WXPay",
            "package1"   =>  "Sign=WXPay",
            "partnerid" =>  $config_pri->wx_mchid,
            "prepayid"  =>  $prepayid,
            "timestamp" =>  time()
        );
        $paramarr2["sign"] = self::wxsign($paramarr2,$config_pri->wx_key);//生成签名
        return self::bulidSuccess($paramarr2);
    }

    private static function createAliPayOrder($order_no,$total_fee,$attach=''){
        $config_pri = getConfigPri();
        //配置参数检测
        if($config_pri->alipay_appid == "" || $config_pri->alipay_prikey == "" || $config_pri->alipay_pubkey == ""){
            return self::bulidFail('暂不支持支付宝支付');
        }
        $options = self::getOptions();
        $options->notifyUrl = $config_pri->pay_notify_domain.'/shop/pay/notify_alipay';
        Factory::setOptions($options);
        $result = Factory::payment()->app()->optional('passback_params',$attach)->pay("商户订单".$order_no,$order_no,strval($total_fee));
        file_put_contents(Env::get('runtime_path')."AliPay_Shop_".date('Y-m-d').".txt",date('y-m-d H:i:s').' 下单返回信息 ch:'.json_encode($result)."\r\n",FILE_APPEND);
        $responseChecker = new ResponseChecker();
        //处理响应或异常
        if ($responseChecker->success($result)) {
            return self::bulidSuccess(['paystr'=>$result->body]);
        } else {
            return self::bulidDataFail($result);
        }
    }
}