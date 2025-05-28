<?php
/**
 * Created by PhpStorm.
 * User: yun7_mac
 * Date: 2019-12-13
 * Time: 15:51
 */

namespace app\api\controller;


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
        //统计app启动次数+1
        $platform = Request::param('platform'); //1-ios  2-安卓
        $statistics = StatisticsModel::where(['id'=>intval(date('Ymd'))])->find();
        if (!$statistics){
            $statistics = new StatisticsModel(['id'=>intval(date('Ymd')),'time'=>nowFormat()]);
            $statistics->save();
        }
        if ($statistics){
            if ($platform == 1){
                $statistics->launch_ios = ['inc',1];
            }elseif ($platform == 2){
                $statistics->launch_android = ['inc',1];
            }
            $statistics->save();
        }

        $configPri = getConfigPri();
        $configTag = getConfigTag();
        $configPub = getConfigPub();
        $configGuard = getConfigGuard();
        //启动页广告
        $launch_ad = AdsModel::where(["status"=>1, "type"=>1])->order("create_time desc")->find();

        //直播分类
        $live_cate = LiveCategoryModel::where(['status'=>1])->order(['sort','id'])->select();

        $configPri->room_notice = $configPub->room_notice;
        $configPri->dl_web_url = $configPub->dl_web_url;
        $configPri->version_android = $configPub->version_android;
        $configPri->version_ios = $configPub->version_ios;
        $configPri->update_info_android = $configPub->update_info_android;
        $configPri->update_info_ios = $configPub->update_info_ios;
        $configPri->share_live_url = $configPub->share_live_url;
        $configPri->share_shortvideo_url = $configPub->share_shortvideo_url;
        $configPri->share_moment_url = $configPub->share_moment_url;
        $configPri->site_domain = $configPub->site_domain;
        $configPri->service_email = $configPub->service_email;
        $configPri->service_phone = $configPub->service_phone;
        $configPri->service_qq = $configPub->service_qq;
        $configPri->service_wechat = $configPub->service_wechat;

        $config = $configPri->visible(["socket_server", 'qcloud_appid', 'cos_bucket', 'cos_region', 'cos_folder_image', 'cos_folder_blurimage', "im_sdkappid", 'wx_appid', 'universal_link', 'qq_appid', 'qq_appkey','room_notice', 'exchange_rate', 'withdraw_min','beauty_channel','tisdk_key','agent_ratio','switch_iap','dl_web_url','share_live_url','share_shortvideo_url','share_moment_url','txim_broadcast','txim_admin','site_domain','version_android','version_ios','update_info_android','update_info_ios','shop_commission','private_level','speak_level','wx_secret','service_email','service_phone','service_qq','service_wechat']);

        return self::bulidSuccess(["config"=>$config, "user_tag"=>$configTag, "launch_ad"=>$launch_ad, 'live_category'=>$live_cate, 'guard_price'=>$configGuard]);
    }

    public function getHomePopAd(){
        $ad = AdsModel::where(["status"=>1, "type"=>3])->order("create_time desc")->find();
        return self::bulidSuccess($ad);
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
            return self::bulidSuccess($tempKeys);
        }else{
            return self::bulidFail();
        }
    }

}