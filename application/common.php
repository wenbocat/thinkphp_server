<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

/* 设置缓存 可自定义时间*/

use app\common\model\AgentModel;
use app\common\model\AnchorLevelRuleModel;
use app\common\model\SmscodeModel;
use app\common\model\UserLevelRuleModel;
use app\common\model\UserModel;
use Qcloud\Sms\SmsSingleSender;
use think\facade\Env;
use Tencent\TLSSigAPIv2;

function redis_set($key, $value, $time=0){
    if($time > 0){
        if(\think\facade\Cache::store('redis')->set($key,json_encode($value),$time)){
            return 1;
        }
        return 0;
    }else{
        if(\think\facade\Cache::store('redis')->set($key,json_encode($value))){
            return 1;
        }
        return 0;
    }
}
/* 获取缓存 */
function redis_get($key){
    return json_decode(\think\facade\Cache::store('redis')->get($key),true);
}

/* 删除缓存 */
function redis_del($key){
    \think\facade\Cache::store('redis')->rm($key);
    return 1;
}

function getConfigPri(){
    $key = 'ConfigPri';
    $configPri_redis = redis_get($key);
    if (!$configPri_redis){
        $configPri = \app\common\model\ConfigPriModel::find(1);
        if(!redis_set($key, $configPri)){
            redis_del($key);
        }
    }else{
        $configPri = new \app\common\model\ConfigPriModel($configPri_redis);
    }
    return $configPri;
}

function getConfigPub(){
    $key = 'ConfigPub';
    $configPub_redis = redis_get($key);
    if (!$configPub_redis){
        $configPub = \app\common\model\ConfigPubModel::find(1);
        if(!redis_set($key, $configPub)){
            redis_del($key);
        }
    }else{
        $configPub = new \app\common\model\ConfigPubModel($configPub_redis);
    }
    return $configPub;
}

function getConfigTag(){
    $key = "ConfigTag";
    $configTag_redis = redis_get($key);
    if (!$configTag_redis){
        $configTag = \app\common\model\ConfigTagModel::order('type','desc')->all();
        if(!redis_set($key, $configTag)){
            redis_del($key);
        }
    }else{
        $configTag = new \app\common\model\ConfigTagModel($configTag_redis);
    }
    return $configTag;
}

function getConfigGuard(){
    $key = 'ConfigGuard';
    $configGuard_redis = redis_get($key);
    if (!$configGuard_redis){
        $configGuard_redis = \app\common\model\GuardPriceModel::find(1);
        if(!redis_set($key, $configGuard_redis)){
            redis_del($key);
        }
    }else{
        $configGuard_redis = new \app\common\model\ConfigPubModel($configGuard_redis);
    }
    return $configGuard_redis;
}

function nowFormat(){
    return date('Y-m-d H:i:s');
}

function createInviteCode(){
    $invite_code = chr(mt_rand(65,90)).chr(mt_rand(65,90)).chr(mt_rand(65,90)).chr(mt_rand(65,90)).chr(mt_rand(65,90)).chr(mt_rand(65,90));
    //查询是否已存在
    $isexist = AgentModel::where('invite_code',$invite_code)->find();
    if ($isexist){
        return createInviteCode();
    }else{
        return $invite_code;
    }
}

function getAnchorRoomID($anchorid){
    return "LIVEROOM_{$anchorid}";
}

function getAnchorIDByGroup($groupid){
    if (strlen($groupid) > 9)
        return substr($groupid,9);
    return false;
}

/**
 *  @desc 腾讯云推拉流地址
 *  @param string $host 协议，如:http、rtmp
 *  @param string $stream 流名,如有则包含 .flv、.m3u8
 *  @param int $type 类型，0表示播流，1表示推流
 */
function CreateLiveUrl($host,$stream,$type){
    $configpri = getConfigPri();
    $push_url_key = $configpri['push_key'];
    $push = $configpri['push_domain'];
    $pull = $configpri['pull_domain'];

    $now_time = time() + 3*60*60;
    $txTime = dechex($now_time);

    $txSecret = md5($push_url_key . $stream . $txTime);
    $safe_url = "&txSecret=" .$txSecret."&txTime=" .$txTime;

    if($type==1){
        $url = "rtmp://{$push}/live/";
        $stream_name = $stream . "?" .$safe_url;
        return [$url,$stream_name];
    }else{
        if($host == 'rtmp'){
            $url = "rtmp://{$pull}/live/" . $stream . "?".$safe_url;
        }else if($host == 'flv'){
            $url = "https://{$pull}/live/" . $stream . ".flv";
        }else if($host == 'm3u8'){
            $url = "https://{$pull}/live/" . $stream . ".m3u8";
        }
    }

    return $url;
}

/*
 * 请求处理
 * @param $url
 * @param $data
 */
function httpHelper($url,$data,$header=null){
    $user_agent = 'Mozilla/4.0+(compatible;+MSIE+6.0;+Windows+NT+5.1;+SV1)';
    $ch=curl_init($url);
    curl_setopt($ch, CURLOPT_POST,true);
    curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch, CURLOPT_TIMEOUT,30);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);		//返回结果
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    if ($header){
        curl_setopt($ch,CURLOPT_HTTPHEADER, $header);
    }
    $r = curl_exec($ch);
    curl_close($ch);
    return $r;
}

/*
 * 请求处理
 * @param $url
 * @param $data
 */
function httpGetHelper($url,$header=null){
    $user_agent = 'Mozilla/4.0+(compatible;+MSIE+6.0;+Windows+NT+5.1;+SV1)';
    $ch=curl_init($url);
    curl_setopt($ch, CURLOPT_POST,false);
    curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch, CURLOPT_TIMEOUT,30);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);		//返回结果
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    if ($header){
        curl_setopt($ch,CURLOPT_HTTPHEADER, $header);
    }
    $r = curl_exec($ch);
    curl_close($ch);
    return $r;
}

// ---------------------------------------------------  业务相关公共方法  --------------------------------------------------------

function sendCode($mobile,$code,$ip){
    try {
        $configPriModel = getConfigPri();
        $ssender = new SmsSingleSender($configPriModel->qsms_appid, $configPriModel->qsms_appkey);
        $params = [$code,'30'];
        $result = $ssender->sendWithParam("86", $mobile, $configPriModel->qsms_tplid,
            $params, $configPriModel->qsms_sign, "", "");
        $rsp = json_decode($result);
        if ($rsp->result == 0){
            file_put_contents(Env::get('runtime_path') . 'sendCode_'.date('Y-m-d').'.txt',date('Y-m-d H:i:s').' : '.$mobile.'  :'.json_encode($rsp)."\r\n",FILE_APPEND);
            $codeModel = new SmscodeModel();
            $codeModel->mobile = $mobile;
            $codeModel->request_ip = $ip;
            $codeModel->create_time = date("Y-m-d H:i:s");
            $codeModel->code = $code;
            $codeModel->save();
            if ($codeModel->id){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    } catch(\Exception $e) {
        file_put_contents(Env::get('runtime_path') . 'sendCode_'.date('Y-m-d').'.txt',date('Y-m-d H:i:s').' : '.$mobile.' :'.json_encode($e)."\r\n",FILE_APPEND);
        return false;
    }
}

//计算主播等级
function calculateAnchorLevel($point){
    $level = 1;
    $rules = AnchorLevelRuleModel::order('level', 'desc')->select();
    foreach ($rules as $rule){
        if ($point >= $rule->point && $rule->level > $level){
            $level = $rule->level;
        }
    }
    return $level;
}

//计算用户等级
function calculateUserLevel($point){
    $level = 1;
    $rules = UserLevelRuleModel::order('level', 'desc')->select();
    foreach ($rules as $rule){
        if ($point >= $rule->point && $rule->level > $level){
            $level = $rule->level;
        }
    }
    return $level;
}

/*
 * 主播直播加经验
 * @param $duration 直播时长 单位S
 */
function addAnchorPointEventLive($duration){
    return $duration/60/6; //一小时涨10点经验
}

//用户每日登陆加经验
function addUserPointEventLogin(){
    return 100;
}

//用户绑定手机加经验
function addUserPointEventBindMobile(){
    return 500;
}

//用户赠送礼物加经验
function addUserPointEventSendGift($price){
    return $price * 10;
}

//用户发布动态加经验
function addUserPointEventPublishMoment(){
    return 100;
}

//用户解锁动态加经验
function addUserPointEventUnlockMoment($price){
    return $price * 10;
}

//用户发布动态加经验
function addUserPointEventPublishShortVideo(){
    return 100;
}

function index_mb_str($str,$start,$end,$encoding){
    if(mb_strlen($str)>$end){
        $string=mb_substr($str,$start,$end,$encoding).'....';
    }else{
        $string=mb_substr($str,$start,$end,$encoding);
    }
    return $string;
}


// ---------------------------------------------------  Redis相关公共方法  --------------------------------------------------------

function getAttentCount($uid){
    $key = 'AttentCount_'.$uid;
    $attentCount = redis_get($key);
    if (!$attentCount){
        $attentCount = \app\common\model\AnchorFansModel::where('fansid', $uid)->count();
        redis_set($key,$attentCount);
    }
    return $attentCount;
}

function setAttentCount($uid, $count){
    $key = 'AttentCount_'.$uid;
    if(!redis_set($key,$count)){
        redis_del($key);
    }
}

function getFansCount($uid){
    $key = 'FansCount_'.$uid;
    $fansCount = redis_get($key);
    if (!$fansCount){
        $fansCount = \app\common\model\AnchorFansModel::where('anchorid', $uid)->count();
        redis_set($key,$fansCount);
    }
    return $fansCount;
}

function setFansCount($uid, $count){
    $key = 'FansCount_'.$uid;
    if(!redis_set($key,$count)){
        redis_del($key);
    }
}

function getVisitorCount($uid){
    $key = 'VisitorCount_'.$uid;
    $visitorCount = redis_get($key);
    if (!$visitorCount){
        $visitorCount = \app\common\model\VisitorLogModel::where('uid', $uid)->count();
        redis_set($key,$visitorCount);
    }
    return $visitorCount;
}

function setVisitorCount($uid, $count){
    $key = 'VisitorCount_'.$uid;
    if(!redis_set($key,$count)){
        redis_del($key);
    }
}

function getSendGiftCount($uid){
    $key = 'SendGiftCount_'.$uid;
    $count = redis_get($key);
    if (!$count){
        $count = \app\common\model\GiftLogModel::where('uid', $uid)->sum("spend");
        redis_set($key,$count);
    }
    return $count;
}

function setSendGiftCount($uid, $count){
    $key = 'SendGiftCount_'.$uid;
    if(!redis_set($key,$count)){
        redis_del($key);
    }
}

function getUserLikeMomentIds($uid){
    $key = 'MomentLikeIds_'.$uid;
    $ids = redis_get($key);
    if (!$ids){
        $moments = \app\common\model\MomentLikeModel::where('uid',$uid)->field('momentid')->select()->toArray();
        $ids = array_column($moments,'momentid');
        redis_set($key,$ids);
    }
    return $ids;
}

function setUserLikeMomentIds($uid, $ids=[]){
    $key = 'MomentLikeIds_'.$uid;
    if (count($ids) == 0){
        $moments = \app\common\model\MomentLikeModel::where('uid',$uid)->field('momentid')->select()->toArray();
        $ids = array_column($moments,'momentid');
    }
    if(!redis_set($key,$ids)){
        redis_del($key);
    }
}

function getUserCollectMomentIds($uid){
    $key = 'MomentCollectIds_'.$uid;
    $ids = redis_get($key);
    if (!$ids){
        $moments = \app\common\model\MomentCollectModel::where('uid',$uid)->field('momentid')->select()->toArray();
        $ids = array_column($moments,'momentid');
        redis_set($key,$ids);
    }
    return $ids;
}

function setUserCollectMomentIds($uid, $ids=[]){
    $key = 'MomentCollectIds_'.$uid;
    if (count($ids) == 0){
        $moments = \app\common\model\MomentCollectModel::where('uid',$uid)->field('momentid')->select()->toArray();
        $ids = array_column($moments,'momentid');
    }
    if(!redis_set($key,$ids)){
        redis_del($key);
    }
}

function getUserLikeMomentCommentIds($uid){
    $key = 'MomentCommentLikeIds_'.$uid;
    $ids = redis_get($key);
    if (!$ids){
        $comments = \app\common\model\MomentCommentLikeModel::where('uid',$uid)->field('commentid')->select()->toArray();
        $ids = array_column($comments,'commentid');
        redis_set($key,$ids);
    }
    return $ids;
}

function setUserLikeMomentCommentIds($uid, $ids=[]){
    $key = 'MomentCommentLikeIds_'.$uid;
    if (count($ids) == 0){
        $comments = \app\common\model\MomentCommentLikeModel::where('uid',$uid)->field('commentid')->select()->toArray();
        $ids = array_column($comments,'commentid');
    }
    if(!redis_set($key,$ids)){
        redis_del($key);
    }
}

function getUserLikeShortVideoIds($uid){
    $key = 'ShortVideoLikeIds_'.$uid;
    $ids = redis_get($key);
    if (!$ids){
        $videos = \app\common\model\ShortvideoLikeModel::where('uid',$uid)->field('videoid')->select()->toArray();
        $ids = array_column($videos,'videoid');
        redis_set($key,$ids);
    }
    return $ids;
}

function setUserLikeShortVideoIds($uid, $ids=[]){
    $key = 'ShortVideoLikeIds_'.$uid;
    if (count($ids) == 0){
        $videos = \app\common\model\ShortvideoLikeModel::where('uid',$uid)->field('videoid')->select()->toArray();
        $ids = array_column($videos,'videoid');
    }
    if(!redis_set($key,$ids)){
        redis_del($key);
    }
}

function getUserLikeShortVideoCommentIds($uid){
    $key = 'ShortVideoCommentLikeIds_'.$uid;
    $ids = redis_get($key);
    if (!$ids){
        $comments = \app\common\model\ShortvideoCommentLikeModel::where('uid',$uid)->field('commentid')->select()->toArray();
        $ids = array_column($comments,'commentid');
        redis_set($key,$ids);
    }
    return $ids;
}

function setUserLikeShortVideoCommentIds($uid, $ids=[]){
    $key = 'ShortVideoCommentLikeIds_'.$uid;
    if (count($ids) == 0){
        $comments = \app\common\model\ShortvideoCommentLikeModel::where('uid',$uid)->field('commentid')->select()->toArray();
        $ids = array_column($comments,'commentid');
    }
    if(!redis_set($key,$ids)){
        redis_del($key);
    }
}

function getUserCollectShortVideoIds($uid){
    $key = 'ShortVideoCollectIds_'.$uid;
    $ids = redis_get($key);
    if (!$ids){
        $videos = \app\common\model\ShortvideoCollectModel::where('uid',$uid)->field('videoid')->select()->toArray();
        $ids = array_column($videos,'videoid');
        redis_set($key,$ids);
    }
    return $ids;
}

function setUserCollectShortVideoIds($uid, $ids=[]){
    $key = 'ShortVideoCollectIds_'.$uid;
    if (count($ids) == 0){
        $videos = \app\common\model\ShortvideoCollectModel::where('uid',$uid)->field('videoid')->select()->toArray();
        $ids = array_column($videos,'videoid');
    }
    if(!redis_set($key,$ids)){
        redis_del($key);
    }
}

function getUserAttentAnchorIds($uid){
    $key = 'AttentAnchorIds_'.$uid;
    $ids = redis_get($key);
    if (!$ids){
        $anchors = \app\common\model\AnchorFansModel::where('fansid',$uid)->field('anchorid')->select()->toArray();
        $ids = array_column($anchors,'anchorid');
        redis_set($key,$ids);
    }
    return $ids;
}

function setUserAttentAnchorIds($uid, $ids=[]){
    $key = 'AttentAnchorIds_'.$uid;
    if (count($ids) == 0){
        $anchors = \app\common\model\AnchorFansModel::where('fansid',$uid)->field('anchorid')->select()->toArray();
        $ids = array_column($anchors,'anchorid');
    }
    if(!redis_set($key,$ids)){
        redis_del($key);
    }
}

function setLiveAudience($anchorid,$audienceids=[]){
    $key = 'LiveAudience_'.$anchorid;
    if (count($audienceids) == 0){
        redis_del($key);
    } elseif (!redis_set($key,$audienceids)){
        redis_del($key);
    }
}

function getLiveAudience($anchorid){
    $key = 'LiveAudience_'.$anchorid;
    if (!$audienceids = redis_get($key)){
        $audienceids = [];
    }
    return $audienceids;
}

/**
 *  @desc 腾讯云trtc播放地址
 *  @param string $uid 用户id
 *  @param string $host 协议，如:http、rtmp
 */
function TrtcPlayLiveUrl($uid="", $host = 'trtc', $time = 24){
    $stream = 'st_' . $uid;// 流名
    $configpri      = getConfigPri();
    $push_url_key   = $configpri['push_key'];
    $push           = $configpri['push_domain'];
    $pull           = $configpri['pull_domain'];
    $now_time       = time() + $time * 60 * 60;
    $txTime         = dechex($now_time);
    $txSecret       = md5($push_url_key . $stream . $txTime);
    $safe_url       = "txSecret=" . $txSecret . "&txTime=" . $txTime;
    switch ($host){
        case "webrtc":
            $url = "webrtc://{$pull}/live/{$stream}?{$safe_url}";
            break;
        case "rtmp":
            $url = "rtmp://{$pull}/live/{$stream}?{$safe_url}";
            break;
        case "flv":
            $url = "https://{$pull}/live/{$stream}.flv?{$safe_url}";
            break;
        case "m3u8":
            $url = "https://{$pull}/live/{$stream}.m3u8?{$safe_url}";
            break;
        case "trtc":
            $im_sdkappid = $configpri['im_sdkappid'];
            $im_sdksecret = $configpri['im_sdksecret'];
            $api = new TLSSigAPIv2($im_sdkappid, $im_sdksecret);
            $usersig = $api->genSig($uid);
            $url = "trtc://cloud.tencent.com/play/{$stream}?sdkappid={$im_sdkappid}&userId={$uid}&usersig={$usersig}";
            break;
    }
    return ["url" => $url ,"stream_name" =>  $stream . "?" .$safe_url];
}

// trtc 推流
function TrtcPush($uid){
    $im_sdkappid = getConfigPri()['im_sdkappid'];
    $im_sdksecret = getConfigPri()['im_sdksecret'];
    $api = new TLSSigAPIv2($im_sdkappid, $im_sdksecret);
    $usersig = $api->genSig($uid);
    $stream = 'st_' . $uid;// 流名
    $url = "trtc://cloud.tencent.com/push/{$stream}?sdkappid={$im_sdkappid}&userId={$uid}&usersig={$usersig}";
    return $url;
}

// trtc 拉流
function TrtcPlay($uid, $stream =""){
    $im_sdkappid = getConfigPri()['im_sdkappid'];
    $im_sdksecret = getConfigPri()['im_sdksecret'];
    $api = new TLSSigAPIv2($im_sdkappid, $im_sdksecret);
    $usersig = $api->genSig($uid);
    $stream = empty($stream) ? 'st_' . $uid : $stream;// 流名
    $url = "trtc://cloud.tencent.com/play/{$stream}?sdkappid={$im_sdkappid}&userId={$uid}&usersig={$usersig}";
    return $url;
}