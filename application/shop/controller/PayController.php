<?php


namespace app\shop\controller;


use Alipay\EasySDK\Kernel\Factory;
use Alipay\EasySDK\Kernel\Util\ResponseChecker;
use app\common\model\ShopDepositOrderModel;
use app\common\model\ShopModel;
use app\common\model\ShopOrderModel;
use app\common\model\ShopSuborderModel;
use think\Db;
use think\facade\Env;
use think\facade\Request;

class PayController extends BaseController
{
    /*
     * 微信退款
     */
    public static function wxRefund($transaction_id,$amount,$total_fee,$refunid){
        $url = "https://api.mch.weixin.qq.com/secapi/pay/refund";
        $config_pri = getConfigPri();
        $noceStr = md5(rand(100,1000).time());//获取随机字符串
        $paramarr = array(
            "appid"       =>    $config_pri->wx_appid,
            "mch_id"      =>    $config_pri->wx_mchid,
            "nonce_str"   =>    $noceStr,
            "out_refund_no"=>   $refunid,
            "refund_fee"  =>    $amount*100,
            "total_fee"   =>    $total_fee*100,
            "transaction_id"=>  $transaction_id,
        );
        $sign = self::wxsign($paramarr,$config_pri->wx_key);//生成签名
        $paramarr['sign'] = $sign;
        $paramXml = "<xml>";
        foreach($paramarr as $k => $v){
            $paramXml .= "<" . $k . ">" . $v . "</" . $k . ">";
        }
        $paramXml .= "</xml>";

        $ch = curl_init ();
        curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
        curl_setopt($ch,CURLOPT_SSLCERT, Env::get('root_path').'cert/wxpay_apiclient_cert.pem');
        curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
        curl_setopt($ch,CURLOPT_SSLKEY, Env::get('root_path').'cert/wxpay_apiclient_key.pem');
        @curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE); // 证书检查
        @curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true);  // 从证书中检查SSL加密算法是否存在
        @curl_setopt($ch, CURLOPT_URL, $url);
        @curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        @curl_setopt($ch, CURLOPT_POST, 1);
        @curl_setopt($ch, CURLOPT_POSTFIELDS, $paramXml);
        @$resultXmlStr = curl_exec($ch);
        file_put_contents(Env::get('runtime_path')."WXPayRefund_".date('Y-m-d').".txt",date('y-m-d H:i:s').' 退款申请返回信息 ch:'.$resultXmlStr."\r\n".$paramXml."\r\n",FILE_APPEND);
        curl_close($ch);

        $wx_res = self::xmlToArray($resultXmlStr);

        if ($wx_res['return_code']=='SUCCESS'){
            if ($wx_res['result_code'] == 'SUCCESS'){
                return $wx_res['refund_id'];
            }
            return false;
        }
        return false;
    }

    /*
     * 支付宝退款
     */
    public static function alipayRefund($outTradeNo,$amount){
        Factory::setOptions(self::getOptions());
        $result = Factory::payment()->common()->refund($outTradeNo,$amount);
        file_put_contents(Env::get('runtime_path')."AliPayRefund_".date('Y-m-d').".txt",date('y-m-d H:i:s').' 退款返回信息 ch:'.json_encode($result)."\r\n",FILE_APPEND);
        $responseChecker = new ResponseChecker();
        //处理响应或异常
        if ($responseChecker->success($result)) {
            return $result->tradeNo;
        }
        return false;
    }

    /**
     * 以post方式提交xml到对应的接口url
     *
     * @param string $xml  需要post的xml数据
     * @param string $url  url
     * @param bool $useCert 是否需要证书，默认不需要
     * @param int $second   url执行超时时间，默认30s
     * @throws WxPayException
     */
    private static function postXmlCurl($xml, $url, $useCert = false, $second = 30)
    {
        $ch = curl_init();

        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);

        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,TRUE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,2);//严格校验
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        if($useCert == true){
            //设置证书
            //使用证书：cert 与 key 分别属于两个.pem文件
            //证书文件请放入服务器的非web目录下
            curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
            curl_setopt($ch,CURLOPT_SSLCERT, Env::get('root_path').'cert/wxpay_apiclient_cert.pem');
            curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
            curl_setopt($ch,CURLOPT_SSLKEY, Env::get('root_path').'cert/wxpay_apiclient_key.pem');
        }
        //post提交方式
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        //运行curl
        $data = curl_exec($ch);
        //返回结果
        if($data){
            curl_close($ch);
            return $data;
        } else {
            $error = curl_errno($ch);
            curl_close($ch);
            return $error;
        }
    }

    public function notify_alipay(){
        $param = Request::post();
        file_put_contents(Env::get('runtime_path') . 'AliPayNotify_Shop_'.date('Y-m-d').'.txt',date('Y-m-d H:i:s').":支付宝回调:".json_encode($param,true)."\r\n",FILE_APPEND);
        Factory::setOptions(self::getOptions());
        if (Factory::payment()->common()->verifyNotify($param)){
            $order_no = $param['out_trade_no'];
            $trade_no = $param['trade_no'];
            $trade_status = $param['trade_status'];
            $total_amount = $param['total_amount'];
            if ($trade_status == 'TRADE_SUCCESS'){
                if (array_key_exists('passback_params',$param) && $param['passback_params'] == 'DP'){
                    //商家缴纳保证金
                    $this->handleDP($order_no,$trade_no,2);
                }else{
                    $this->handleOrder($order_no,$trade_no,$total_amount,2);
                }
            }
            echo 'fail';
        }else{
            file_put_contents(Env::get('runtime_path') . 'AliPayNotify_Shop_'.date('Y-m-d').'.txt',date('Y-m-d H:i:s').":验签失败:".json_encode($param,true)."\r\n",FILE_APPEND);
            echo 'fail';
        }
    }

    public function notify_wxpay(){
        $xml = file_get_contents("php://input");
        file_put_contents(Env::get('runtime_path') . 'WxPayNotify_Shop_'.date('Y-m-d').'.txt',date('Y-m-d H:i:s').":微信支付回调:".$xml."\r\n",FILE_APPEND);
        //解析xml
        $data = self::xmlToArray($xml);
        if($data['return_code'] == "SUCCESS"){
            $configpri = getConfigPri();
            $wxSign = $data['sign'];
            unset($data['sign']);
            $data['appid']  =  $configpri['wx_appid'];
            $data['mch_id'] =  $configpri['wx_mchid'];
            $key =  $configpri['wx_key'];
            ksort($data);//按照字典排序参数数组
            $sign = self::wxsign($data,$key);//生成签名
            if($this -> checkSign($wxSign,$sign)){
                if ($data['attach'] == 'DP'){
                    //商家缴纳保证金
                    $this->handleDP($data['out_trade_no'], $data['transaction_id'], 1);
                }else {
                    $this->handleOrder($data['out_trade_no'], $data['transaction_id'], intval($data['cash_fee'])/100, 1);
                }
                exit;
            }else{
                echo $this -> returnWxInfo("FAIL","签名失败");
                file_put_contents(Env::get('runtime_path') . 'WxPayNotify_Shop_'.date('Y-m-d').'.txt',date('Y-m-d H:i:s').":微信支付回调:签名失败\r\n",FILE_APPEND);
                exit;
            }
        }else{
            echo $this -> returnWxInfo("FAIL","签名失败");
            file_put_contents(Env::get('runtime_path') . 'WxPayNotify_Shop_'.date('Y-m-d').'.txt',date('Y-m-d H:i:s').":微信支付回调:签名失败\r\n",FILE_APPEND);
            exit;
        }
    }

    private function returnWxInfo($type,$msg){
        if($type == "SUCCESS"){
            return $returnXml = "<xml><return_code><![CDATA[{$type}]]></return_code></xml>";
        }else{
            return $returnXml = "<xml><return_code><![CDATA[{$type}]]></return_code><return_msg><![CDATA[{$msg}]]></return_msg></xml>";
        }
    }

    private function checkSign($sign1,$sign2){
        return trim($sign1) == trim($sign2);
    }

    private function handleDP($order_no,$trade_no,$channel){
        $order = ShopDepositOrderModel::where(['order_no'=>$order_no])->find();
        if (!$order){
            if ($channel == 1){
                echo $this -> returnWxInfo("FAIL","");
            }elseif ($channel == 2){
                echo 'fail';
            }
            exit;
        }
        if ($order->pay_status == 1){
            //订单已处理过
            if ($channel == 1){
                echo $this -> returnWxInfo("SUCCESS","OK");
            }elseif ($channel == 2){
                echo 'success';
            }
            exit;
        }
        $order->trade_no = $trade_no;
        $order->pay_time = nowFormat();
        $order->pay_status = 1;

        $shop = new ShopModel(['id'=>$order->shopid,'profit'=>0.00,'total_profit'=>0.00,'create_time'=>nowFormat()]);

        Db::startTrans();
        if ($order->save() && $shop->save()){
            Db::commit();
            if ($channel == 1){
                echo $this -> returnWxInfo("SUCCESS","OK");
            }elseif ($channel == 2){
                echo 'success';
            }
            exit;
        }else{
            Db::rollback();
            if ($channel == 1){
                echo $this -> returnWxInfo("FAIL","");
            }elseif ($channel == 2){
                echo 'fail';
            }
            exit;
        }
    }

    private function handleOrder($order_no,$trade_no,$total_amount,$channel){
        if (strpos($order_no,'NS') === 0){
            //母订单支付
            $order = ShopOrderModel::where(['order_no'=>$order_no])->find();
            if (!$order){
                if ($channel == 1){
                    echo $this -> returnWxInfo("FAIL","");
                }elseif ($channel == 2){
                    echo 'fail';
                }
                exit;
            }
            if ($order->pay_status == 1){
                //订单已处理过
                if ($channel == 1){
                    echo $this -> returnWxInfo("SUCCESS","OK");
                }elseif ($channel == 2){
                    echo 'success';
                }
                exit;
            }
            if ($total_amount < $order->total_price - 1){
                if ($channel == 1){
                    echo $this -> returnWxInfo("FAIL","");
                }elseif ($channel == 2){
                    echo 'fail';
                }
                exit;
            }
            $order->pay_channel = $channel;
            $order->pay_status = 1;
            $order->pay_amount = $total_amount;
            $order->pay_no = $trade_no;
            $order->pay_time = nowFormat();
            Db::startTrans();
            if (!$order->save()){
                Db::rollback();
                echo 'fail';
                exit;
            }
            $suborders = ShopSuborderModel::where(['parentid'=>$order->id])->select();
            foreach ($suborders as $suborder){
                $suborder->pay_time = $order->pay_time;
                $suborder->pay_channel = $order->pay_channel;
                $suborder->pay_no = $order->pay_no;
                $suborder->status = 1;
                $suborder->pay_type = 1;
                if (!$suborder->save()){
                    Db::rollback();
                    echo 'fail';
                    exit;
                }
            }
            Db::commit();
            if ($channel == 1){
                echo $this -> returnWxInfo("SUCCESS","OK");
                exit;
            }elseif ($channel == 2){
                echo 'success';
                exit;
            }
        }else{
            //子订单支付
            $suborder = ShopSuborderModel::where(['order_no'=>$order_no])->find();
            if (!$suborder){
                if ($channel == 1){
                    echo $this -> returnWxInfo("FAIL","");
                }elseif ($channel == 2){
                    echo 'fail';
                }
                exit;
            }
            if ($suborder->status != 0 && $suborder->status != 2){
                //订单已处理过
                if ($channel == 1){
                    echo $this -> returnWxInfo("SUCCESS","OK");
                }elseif ($channel == 2){
                    echo 'success';
                }
                exit;
            }
            if ($total_amount < $suborder->total_price - 1){
                if ($channel == 1){
                    echo $this -> returnWxInfo("FAIL","");
                }elseif ($channel == 2){
                    echo 'fail';
                }
                exit;
            }
            $suborder->pay_time = nowFormat();
            $suborder->pay_channel = $channel;
            $suborder->pay_no = $trade_no;
            $suborder->pay_amount = $total_amount;
            $suborder->status = 1;
            $suborder->pay_type = 2;
            if ($suborder->save()){
                if ($channel == 1){
                    echo $this -> returnWxInfo("SUCCESS","OK");
                    exit;
                }elseif ($channel == 2){
                    echo 'success';
                    exit;
                }
            }
        }
    }
}