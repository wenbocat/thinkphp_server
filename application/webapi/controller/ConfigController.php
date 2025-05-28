<?php
/**
 * Created by PhpStorm.
 * User: yun7_mac
 * Date: 2019-12-13
 * Time: 15:51
 */

namespace app\webapi\controller;


use app\common\model\AdsModel;
use app\common\model\LiveCategoryModel;
use app\common\model\StatisticsModel;
use app\common\QcloudSTS;
use think\facade\Request;

class ConfigController extends BaseController
{
    protected $NeedLogin = ['getSignForVod','getTempKeysForCos'];

    protected $rules = array(

    );

    public function initialize()
    {
        parent::initialize();
    }

    public function getCommonConfig(){
        $configPri = getConfigPri();
        $configPub = getConfigPub();


        $config = $configPri->visible(['qcloud_appid', 'cos_bucket', 'cos_region', 'cos_folder_image', 'cos_folder_blurimage', "im_sdkappid", 'wx_appid', 'universal_link', 'qq_appid', 'qq_appkey', 'exchange_rate', 'withdraw_min','beauty_channel','tisdk_key','agent_ratio'])->toArray();

        $config['room_notice'] = $configPub->room_notice;
        $config['dl_qrcode'] = $configPub->dl_qrcode;
        $config['dl_web_url'] = $configPub->dl_web_url;
        $config['dl_android'] = $configPub->dl_android;
        $config['dl_ios'] = $configPub->dl_ios;
        $config['version_android'] = $configPub->version_android;
        $config['version_ios'] = $configPub->version_ios;
        $config['copyright'] = $configPub->copyright;
        $config['service_email'] = $configPub->service_email;
        $config['service_phone'] = $configPub->service_phone;
        $config['service_qq'] = $configPub->service_qq;
        $config['site_domain'] = $configPub->site_domain;

        return self::bulidSuccess(["config"=>$config]);
    }

    public function getSignForVod(){
        $configPriModel = getConfigPri();

        $secret_id = $configPriModel->qcloud_secretid;
        $secret_key = $configPriModel->qcloud_secretkey;

        // 确定签名的当前时间和失效时间
        $current = time();
        $expired = $current + 86400;  // 签名有效期：1天

        // 向参数列表填入参数
        $arg_list = array(
            "secretId" => $secret_id,
            "currentTimeStamp" => $current,
            "expireTime" => $expired,
            "random" => rand());

        // 计算签名
        $original = http_build_query($arg_list);
        $signature = base64_encode(hash_hmac('SHA1', $original, $secret_key, true).$original);

        if ($signature){
            return self::bulidSuccess(['signature'=>$signature]);
        }else{
            return self::bulidFail();
        }
    }

    public function getTempKeysForCos(){
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
            return self::bulidSuccess($tempKeys);
        }else{
            return self::bulidFail();
        }
    }
}