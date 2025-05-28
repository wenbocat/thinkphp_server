<?php
/**
 * Created by PhpStorm.
 * User: yun7_mac
 * Date: 2019-06-14
 * Time: 17:01
 */

namespace app\guild\controller;

use app\common\model\GuildModel;
use app\common\QcloudSTS;
use think\Controller;
use think\exception\HttpResponseException;
use think\facade\Cookie;
use think\facade\Request;
use think\facade\Log;
use think\Response;
use think\Validate;

class BaseController extends Controller
{

    protected $userinfo;

    /**
     * 参数校验规则
     */
    protected $rules = [];

    protected $OPENSSL_KEY = 'meet';

    /**
     * 初始化操作
     */
    protected function initialize() {
        $token = Cookie::get("guildToken");
        $guildid = Cookie::get("guildid");
        if (!$guildid || !$token){
            $this->redirect("/guild/Login/redirectpage");
        }
        $this->userinfo = GuildModel::where(['id'=>$guildid,'token'=>$token,'status'=>1])->find();
        if (!$this->userinfo){
            Log::write("id=".$guildid.",token=".$token);
            $this->redirect("/guild/Login/redirectpage");
        }

        if (!$this->check_params()){
            $json = json_encode(array("code"=>"1","msg"=>"参数不全"));
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
    private function matchFuc($arr = []) {
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
        return json(["code"=>1,"msg"=>$message]);
    }

    static function bulidDataFail($data=[], $message="操作失败，请稍后再试"){
        return json(["status"=>1,"msg"=>$message,'data'=>$data]);
    }

    static function bulidSuccess($data=[],$message="操作成功"){
        return json(["code"=>0,"msg"=>$message,"data"=>$data]);
    }

    static function bulidLoginTimeOut($message="登录超时，请重新登录"){
        return json(["code"=>2,"msg"=>$message]);
    }

    static function echoSuccess($data=['st'=>0],$msg="操作成功"){
        echo json_encode(["code"=>0,"data"=>$data,'msg'=>$msg]);
    }


    // 公共api  ----------------------------------------------------------------------------------------

    public function signForCos(){
        if ($tempKeys = self::getSignForCos()){
            return self::bulidSuccess($tempKeys);
        }else{
            return self::bulidFail("获取上传权限失败");
        }
    }

    static function getSignForCos(){
        $configPriModel = getConfigPri();
        $sts = new QcloudSTS();
        $config = array(
            'url' => 'https://sts.tencentcloudapi.com/',
            'domain' => 'sts.tencentcloudapi.com',
            'proxy' => '',
            'secretId' => $configPriModel->qcloud_secretid, // 固定密钥
            'secretKey' => $configPriModel->qcloud_secretkey, // 固定密钥
            'bucket' => $configPriModel->cos_bucket, // 换成你的 bucket
            'region' => $configPriModel->cos_region, // 换成 bucket 所在园区
            'durationSeconds' => 1800, // 密钥有效期
            'allowPrefix' => "*", // 这里改成允许的路径前缀，可以根据自己网站的用户登录态判断允许上传的具体路径，例子： a.jpg 或者 a/* 或者 * (使用通配符*存在重大安全风险, 请谨慎评估使用)
            // 密钥的权限列表。简单上传和分片需要以下的权限，其他权限列表请看 https://cloud.tencent.com/document/product/436/31923
            'allowActions' => array (
                // 简单上传
                'name/cos:PutObject',
            )
        );

        // 获取临时密钥，计算签名
        $tempKeys = $sts->getTempKeys($config);
        if ($tempKeys){
            $tempKeys["bucket"] = $configPriModel->cos_bucket;
            $tempKeys["region"] = $configPriModel->cos_region;
            $tempKeys["filename"] = date("YmdHis").rand(10000,99999);
            return $tempKeys;
        }else{
            return null;
        }
    }
}