<?php
/**
 * Created by PhpStorm.
 * User: yun7_mac
 * Date: 2019-06-14
 * Time: 17:01
 */

namespace app\webapi\controller;

use Alipay\EasySDK\Kernel\Config;
use think\Controller;
use think\facade\Request;
use think\Response;
use think\exception\HttpResponseException;
use think\Validate;

use app\common\model\UserModel;

header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Methods:GET, POST, OPTIONS, DELETE");
header("Access-Control-Allow-Headers:DNT,X-Mx-ReqToken,Keep-Alive,User-Agent,X-Requested-With,If-Modified-Since,Cache-Control,Content-Type, Accept-Language, Origin, Accept-Encoding");

class BaseController extends Controller
{

    protected $userinfo;

    /**
     * 需要登录的方法
     */
    protected $NeedLogin = [];
    /**
     * 参数校验规则
     */
    protected $rules = [];

    protected $OPENSSL_KEY = 'meet';
    protected $API_SECKETKEY = 'meetT6WjnM7a0482cdaaa0c649750';

    /**
     * 初始化操作
     */
    protected function initialize() {
        if ($this->match($this->NeedLogin)) {
            $token = Request::param("token");
            $uid = Request::param("uid");
            if (!$uid || !$token){
                $json = json_encode(array("status"=>"2","msg"=>"登录超时，请重新登录"));
                $response = Response::create($json);
                throw new HttpResponseException($response);
            }
            $this->userinfo = UserModel::where("id",$uid)->where("token",$token)->find();
            if (!$this->userinfo){
                $json = json_encode(array("status"=>"2","msg"=>"登录超时，请重新登录"));
                $response = Response::create($json);
                throw new HttpResponseException($response);
            }
        }
        if (!$this->check_params()){
            $json = json_encode(array("status"=>"1","msg"=>"参数不全"));
            $response = Response::create($json);
            throw new HttpResponseException($response);
        }
    }

    /**
     * 检测当前控制器和方法是否匹配传递的数组
     *
     * @param array $arr 需要验证权限的数组
     * @return boolean
     */
    private function match($arr = []) {
        $arr = is_array($arr) ? $arr : explode(',', $arr);
        if (!$arr)
        {
            return FALSE;
        }
        $arr = array_map('strtolower', $arr);
        // 是否存在
        if (in_array(strtolower(Request::action()), $arr) || in_array('*', $arr))
        {
            return TRUE;
        }

        // 没找到匹配
        return FALSE;
    }

    /**
     * [check_params 验证参数  参数过滤]
     * @return [return]      [合格的参数数组]
     */
    public function check_params(){
        if (array_key_exists(Request::action(),$this->rules)){
            $rule = $this->rules[Request::action()];
            $validater = new Validate($rule);
            if (!$validater->check(Request::param())) {
                return false;
            }
        }
        // 如果正确，通过验证
        return true;

    }

    static function bulidFail($message="操作失败，请稍后再试"){
        return json(["status"=>1,"msg"=>$message]);
    }

    static function bulidCodeFail($code=1,$message="操作失败，请稍后再试"){
        return json(["status"=>$code,"msg"=>$message]);
    }

    static function bulidDataFail($data=[], $message="操作失败，请稍后再试"){
        return json(["status"=>1,"msg"=>$message,'data'=>$data]);
    }

    //余额不足
    static function bulidChargeFail($message="余额不足，请前往充值"){
        return json(["status"=>1001,"msg"=>$message]);
    }

    static function bulidSuccess($data=[],$msg=""){
        return json(["status"=>0,"data"=>$data,'msg'=>$msg]);
    }

    static function bulidLoginTimeOut($message="登录超时，请重新登录"){
        return json(["status"=>2,"msg"=>$message]);
    }

    static function echoSuccess($data=['st'=>0],$msg=""){
        echo json_encode(["status"=>0,"data"=>$data,'msg'=>$msg]);
    }

    //支付宝初始化配置
    static protected function getOptions(){
        $config_pri = getConfigPri();

        $options = new Config();
        $options->protocol = 'https';
        $options->gatewayHost = 'openapi.alipay.com';
        $options->signType = 'RSA2';

        $options->appId = $config_pri->alipay_appid;

        // 为避免私钥随源码泄露，推荐从文件中读取私钥字符串而不是写入源码中
        $options->merchantPrivateKey = $config_pri->alipay_prikey;

        //注：如果采用非证书模式，则无需赋值上面的三个证书路径，改为赋值如下的支付宝公钥字符串即可
        $options->alipayPublicKey = $config_pri->alipay_pubkey;

        //可设置异步通知接收服务地址（可选）
        $options->notifyUrl = $config_pri->pay_notify_domain.'/api/paynotify/notify_alipay';

        //可设置AES密钥，调用AES加解密相关接口时需要（可选）
//        $options->encryptKey = "<-- 请填写您的AES密钥，例如：aa4BtZ4tspm2wnXLb1ThQA== -->";

        return $options;
    }

    /* 生成订单号 */
    static protected function createOrderNo($uid){
        $orderid=$uid.date('YmdHis').rand(1000,9999);
        return $orderid;
    }

    /**
     * 生成签名
     * @param $values
     * @return string 本函数不覆盖sign成员变量，如要设置签名需要调用SetSign方法赋值
     */
    static protected function makeSign($values, $key)
    {
        //签名步骤一：按字典序排序参数
        ksort($values);
        $string = self::toUrlParams($values);
        //签名步骤二：在string后加入KEY
        $string = $string . '&key=' . $key;
        //签名步骤三：MD5加密
        $string = md5($string);
        //签名步骤四：所有字符转为大写
        $result = strtoupper($string);
        return $result;
    }

    /**
     * 格式化参数格式化成url参数
     * @param $values
     * @return string
     */
    static private function toUrlParams($values)
    {
        $buff = '';
        foreach ($values as $k => $v) {
            if ($k != 'sign' && $v != '' && !is_array($v)) {
                $buff .= $k . '=' . $v . '&';
            }
        }
        return trim($buff, '&');
    }

    /**
     * sign拼装获取
     */
    static protected function wxsign($param,$key){
        $sign = "";
        foreach($param as $k => $v){
            $sign .= $k."=".$v."&";
        }
        $sign .= "key=".$key;
        $sign = strtoupper(md5($sign));
        return $sign;

    }
    /**
     * xml转为数组
     */
    static protected function xmlToArray($xmlStr){
        $postStr = $xmlStr;
        $obj = (array)simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
        return $obj;
    }
}