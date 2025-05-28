<?php


namespace app\api\controller;

use Alipay\EasySDK\Kernel\Factory;
use app\common\model\AgentModel;
use app\common\model\AgentProfitModel;
use app\common\model\OrderModel;
use app\common\model\UserModel;
use app\common\TXService;
use think\Db;
use think\facade\Env;
use think\facade\Request;

class PaynotifyController extends BaseController
{
    public function notify_alipay(){
        $param = Request::post();
        file_put_contents(Env::get('runtime_path') . 'AliPayNotify_'.date('Y-m-d').'.txt',date('Y-m-d H:i:s').":支付宝回调:".json_encode($param,true)."\r\n",FILE_APPEND);
        Factory::setOptions(self::getOptions());
        if (Factory::payment()->common()->verifyNotify($param)){
            $order_no = $param['out_trade_no'];
            $trade_no = $param['trade_no'];
            $trade_status = $param['trade_status'];
            $total_amount = $param['total_amount'];
            if ($trade_status == 'TRADE_SUCCESS'){
                $this->handleOrder($order_no,$trade_no,$total_amount,2);
            }
            echo 'fail';
        }else{
            file_put_contents(Env::get('runtime_path') . 'AliPayNotify_'.date('Y-m-d').'.txt',date('Y-m-d H:i:s').":验签失败:".json_encode($param,true)."\r\n",FILE_APPEND);
            echo 'fail';
        }
    }

    public function notify_wxpay(){
        $xml = file_get_contents("php://input");
        file_put_contents(Env::get('runtime_path') . 'WxPayNotify_'.date('Y-m-d').'.txt',date('Y-m-d H:i:s').":微信支付回调:".$xml."\r\n",FILE_APPEND);
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
                $this -> handleOrder($data['out_trade_no'],$data['transaction_id'],intval($data['cash_fee'])/100,1);
                exit;
            }else{
                echo $this -> returnWxInfo("FAIL","签名失败");
                file_put_contents(Env::get('runtime_path') . 'WxPayNotify_'.date('Y-m-d').'.txt',date('Y-m-d H:i:s').":微信支付回调:签名失败\r\n",FILE_APPEND);
                exit;
            }
        }else{
            echo $this -> returnWxInfo("FAIL","签名失败");
            file_put_contents(Env::get('runtime_path') . 'WxPayNotify_'.date('Y-m-d').'.txt',date('Y-m-d H:i:s').":微信支付回调:签名失败\r\n",FILE_APPEND);
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

    private function handleOrder($order_no,$trade_no,$total_amount,$channel){
        $order = OrderModel::where(['order_no'=>$order_no])->find();
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
        //订单处理业务逻辑
        $order->pay_amount = $total_amount;
        $order->out_trade_no = $trade_no;
        $order->pay_time = nowFormat();
        $order->pay_status = 1;

        $user = UserModel::where(['id'=>$order->uid])->find();
        $user->gold = ['inc',$order->gold+$order->gold_added];

        if ($order->type == 1 && $order->vip_level > 0){
            //订单类型：开通vip
            $user->vip_level = $order->vip_level;
            if ($user->vip_date && strtotime($user->vip_date)>time()){
                $newtime = strtotime($user->vip_date) + 30*24*60*60;
            }else{
                $newtime = time() + 30*24*60*60;
            }
            $user->vip_date = date('Y-m-d H:i:s',$newtime);
        }

        //代理返佣
        $agent = AgentModel::where(['uid'=>$user->agentid])->find();
        $is_salesman = UserModel::where(['id'=>$user->agentid])->value('is_salesman');
        if ($agent && $is_salesman == 0){
            $config_pri = getConfigPri();
            $profit = round($config_pri->agent_ratio * $total_amount) * 0.01;
            $agent->profit = ['inc',$profit];
            $agent->total_profit = ['inc',$profit];
            $agentProfit = new AgentProfitModel(['agentid'=>$agent->uid,'profit'=>$profit,'content'=>"下级用户(ID:{$user->id})充值奖励",'create_time'=>nowFormat()]);
            Db::startTrans();
            if ($agent->save() && $agentProfit->save()){
                Db::commit();
            }else{
                Db::rollback();
            }
        }

        Db::startTrans();
        if ($order->save() && $user->save()){
            Db::commit();
            if ($channel == 1){
                echo $this -> returnWxInfo("SUCCESS","OK");
            }elseif ($channel == 2){
                echo 'success';
            }
            //全频道推送
            if ($order->type == 1 && $order->vip_level > 0){
                //调用IM推送
                $users = UserModel::where(['online_status'=>1])->field('id')->select()->toArray();
                $uidArr = explode(',',implode(',',array_column($users,'id')));
                for ($i = 0; $i<count($uidArr);){
                    $pushids = array_slice($uidArr, $i, 500);
                    $i += 500;
                    $elem = TXService::buildCustomElem('BroadcastStreamer',['streamer'=>['user'=>$user->visible(['nick_name','avatar','id']),'vip'=>['level'=>$order->vip_level],'type'=>1]],'全频道广播','');
                    $result = TXService::sendBroadcast($pushids,$elem);
                    file_put_contents(Env::get('runtime_path') . 'BroadcastStreamer_'.date('Y-m-d').'.txt',date('Y-m-d H:i:s').":全频道广播:".json_encode($result,true)."\r\n",FILE_APPEND);
                }
            }
            exit;
        }else{
            Db::rollback();
        }
    }
}