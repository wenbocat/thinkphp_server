<?php
/**
 * Created by PhpStorm.
 * User: yun7_mac
 * Date: 2019-11-08
 * Time: 15:34
 */

namespace app\api\controller;

use app\common\QcloudSTS;

class QcloudController extends BaseController
{
    protected $NeedLogin = ['signForCos'];

    protected $rules = array(
        'signForCos'=>array(
            'uid'=>'require|length:1,11',
            'token'=>'require',
        ),
    );

    public function initialize()
    {
        parent::initialize();
    }

    public function signForCos(){
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
            'allowPrefix' => $configPriModel->cos_appfolder, // 这里改成允许的路径前缀，可以根据自己网站的用户登录态判断允许上传的具体路径，例子： a.jpg 或者 a/* 或者 * (使用通配符*存在重大安全风险, 请谨慎评估使用)
            // 密钥的权限列表。简单上传和分片需要以下的权限，其他权限列表请看 https://cloud.tencent.com/document/product/436/31923
            'allowActions' => array (
                // 简单上传
                'name/cos:PutObject',
            )
        );

        // 获取临时密钥，计算签名
        $tempKeys = $sts->getTempKeys($config);
        if ($tempKeys){
            $tempKeys->bucket = $configPriModel->cos_bucket;
            $tempKeys->region = $configPriModel->cos_region;
            return self::bulidSuccess($tempKeys);
        }else{
            return self::bulidFail("获取上传权限失败");
        }
    }
}