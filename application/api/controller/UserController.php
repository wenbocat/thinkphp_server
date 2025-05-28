<?php
/**
 * Created by PhpStorm.
 * User: yun7_mac
 * Date: 2019-11-07
 * Time: 10:13
 */

namespace app\api\controller;


use app\common\model\AgentModel;
use app\common\model\AnchorFansModel;
use app\common\model\AnchorGuardianModel;
use app\common\model\AnchorLevelRuleModel;
use app\common\model\ConfigTagModel;
use app\common\model\GiftLogModel;
use app\common\model\LiveHistoryModel;
use app\common\model\LiveModel;
use app\common\model\LiveRoomManagerModel;
use app\common\model\MomentModel;
use app\common\model\ShortvideoModel;
use app\common\model\SmscodeModel;
use app\common\model\StatisticsModel;
use app\common\model\UserAuthModel;
use app\common\model\UserLevelRuleModel;
use app\common\model\UserModel;
use app\common\model\UserPhotoModel;
use app\common\model\UserProfileModel;
use app\common\model\UserProfitModel;
use app\common\model\VisitorLogModel;
use think\Db;
use think\facade\Env;
use think\facade\Request;

use Qcloud\Sms\SmsSingleSender;

class UserController extends BaseController
{
    protected $NeedLogin = ['getUserInfo', 'editUserInfo','bindPhone','giftProfit','momentProfit','userAuthInfo','sendVerifyCode','getLiveLog','getFans','myMoment','myVideo','getUserLevelInfo','getManagedRooms','bindAgent'];

    protected $rules = array(
        'login'=>array(
            'account'=>'require|length:6,18',
            'pwd'=>'require|length:6,18'
        ),
        'autologin'=>array(
            'uid'=>'require|length:6,18',
            'token'=>'require'
        ),
        'wxlogin'=>array(
            'code'=>'require'
        ),
        'regist'=>array(
            'account'=>'require|length:6,18',
            'pwd'=>'require|length:6,18',
            'smscode'=>'require|length:6',
        ),
        'sendcode'=>array(
            'mobile'=>'require|length:6,18',
        ),
        'sendverifycode'=>array(
            'mobile'=>'require|length:6,18',
        ),
        'bindphone'=>array(
            'mobile'=>'require|length:6,18',
            'pwd'=>'require|length:6,18',
            'smscode'=>'require',
        ),
        'changepwd'=>array(
            'mobile'=>'require|length:6,18',
            'pwd'=>'require|length:6,18',
            'smscode'=>'require',
        ),
    );

    public function initialize()
    {
        parent::initialize();
    }

    public function login(){
        $account = Request::param("account");
        $pwd = Request::param("pwd");

        $user = UserModel::where("account",$account)->where("password",openssl_encrypt(substr($account,strlen($account)-4,4).$pwd,"DES-ECB",$this->OPENSSL_KEY))->with('profile')->field("password, wx_unionid, qq_unionid",true)->find();

        if (!$user) {
            return self::bulidFail('账号或密码有误');
        }

        if ($user->status == 1){
            return self::bulidFail("账号状态异常");
        }

        $user->token = strtoupper(openssl_encrypt(date("YmdHis").rand(10000,99999),"DES-ECB",$this->OPENSSL_KEY));
        $user->last_login = nowFormat();
        $user->login_platform = Request::param('login_platform');
        $user->ip = request()->ip();
        $user->save();

        //关注数量
        $user->attent_count = count(getUserAttentAnchorIds($user->id));
        //粉丝数量
        $user->fans_count = getFansCount($user->id);
        //送礼数量
        $user->gift_spend = getSendGiftCount($user->id);
        //访客数量
        $user->visitor_count = getVisitorCount($user->id);

        //守护的主播
        $guardAnchors = AnchorGuardianModel::where(['uid'=>$user->id])->whereTime('expire_time','>',nowFormat())->field('anchorid')->select()->toArray();
        if ($guardAnchors) {
            $user->guard_anchors = array_column($guardAnchors, 'anchorid');
        }

        $configPri = getConfigPri();
        $api = new \Tencent\TLSSigAPIv2($configPri->im_sdkappid, $configPri->im_sdksecret);
        $sig = $api->genSig($user->id);
        $user->txim_sign = $sig;

        $user = $user->toArray();
        if ($user['tags']){
            //主播形象标签
            $user_tags = explode(',',$user['tags']);
            $tags = ConfigTagModel::where('id', 'in', $user_tags)->select();
            $user['tags'] = $tags;
        }

        return self::bulidSuccess($user);
    }

    public function regist(){
        $user = new UserModel();
        $user->account = Request::param("account");
        $user->password = openssl_encrypt(substr($user->account,strlen($user->account)-4,4).Request::param("pwd"),"DES-ECB",$this->OPENSSL_KEY);
        $user->nick_name = '手机用户'.substr(Request::param("account"),7,4);
        $user->token = strtoupper(openssl_encrypt(date("YmdHis").rand(10000,99999),"DES-ECB",$this->OPENSSL_KEY));
        $user->regist_time = nowFormat();
        $user->last_login = nowFormat();
        $user->login_platform = Request::param('login_platform');
        $user->point = addUserPointEventBindMobile(); //手机号注册 直接完成绑定手机号任务
        $user->user_level = calculateUserLevel($user->point);
        $user->ip = request()->ip();

        if ($invite_code = Request::param('invite_code')){
            $parent = AgentModel::where(['invite_code'=>$invite_code])->find();
            if ($parent){
                $user->agentid = $parent->uid;
            }
        }

        //验证手机号
        $usercheck = UserModel::where("account",$user->account)->find();
        if ($usercheck){
            return self::bulidFail("手机号已被注册");
        }
        //验证码
        $codeinfo = SmscodeModel::where("mobile",$user->account)->where("code",Request::param("smscode"))->where("status",0)->whereTime("create_time","-30 minutes")->order("create_time","desc")->find();
        if (!$codeinfo){
            return self::bulidFail("验证码有误或已过期");
        }
        $codeinfo->status = 1;
        $codeinfo->save();

        Db::startTrans();

        //新增用户
        if ($user->save()){
            //统计注册用户数+1
            $platform = Request::param('platform'); //1-ios  2-安卓
            $statistics = StatisticsModel::where(['id'=>intval(date('Ymd'))])->find();
            if (!$statistics){
                $statistics = new StatisticsModel(['id'=>intval(date('Ymd')),'time'=>nowFormat()]);
                $statistics->save();
            }
            if ($statistics){
                if ($platform == 1){
                    $statistics->regist_ios = ['inc',1];
                }elseif ($platform == 2){
                    $statistics->regist_android = ['inc',1];
                }
                $statistics->save();
            }

            $profile = new UserProfileModel(['uid'=>$user->id]);

            $invite_code = createInviteCode();
            $agent = new AgentModel(['uid'=>$user->id,'profit'=>0,'total_profit'=>0,'invite_code'=>$invite_code]);

            if ($profile->save() && $agent->save()){
                Db::commit();

                $user = UserModel::find($user->id);
                $configPri = getConfigPri();
                $api = new \Tencent\TLSSigAPIv2($configPri->im_sdkappid, $configPri->im_sdksecret);
                $sig = $api->genSig($user->id);
                $user->txim_sign = $sig;
                $user->profile = $profile;
                return self::bulidSuccess($user);
            }else{
                Db::rollback();
                return self::bulidFail("注册失败");
            }
        }else{
            Db::rollback();
            return self::bulidFail("注册失败");
        }
    }


    public function sendCode(){

        $mobile = Request::param("mobile");
        $ip = Request::ip();
        //查询最近5分钟内是否发送过
        $codeInfo1 = Db::table("db_smscode")->where("mobile",$mobile)->whereTime("create_time","-1 minutes")->where("status",0)->select();
        if (count($codeInfo1)>0){
            return self::bulidFail("验证码发送过于频繁，请稍后再试");
        }
        $codeInfo2 = Db::table("db_smscode")->where("request_ip",$ip)->whereTime("create_time","-1 minutes")->where("status",0)->select();
        if (count($codeInfo2)>0){
            return self::bulidFail("验证码发送过于频繁，请稍后再试");
        }
        $code = rand(100000,999999);
        $ret = sendCode($mobile,$code,$ip);
        if ($ret){
            return self::bulidSuccess();
        }else{
            return self::bulidFail('发送失败');
        }
    }

    public function sendVerifyCode(){
        $mobile = Request::param("mobile");
        if ($mobile != $this->userinfo->account){
            return self::bulidFail('该手机号与当前登录账号不一致');
        }
        $ip = Request::ip();
        //查询最近5分钟内是否发送过
        $codeInfo1 = Db::table("db_smscode")->where("mobile",$mobile)->whereTime("create_time","-1 minutes")->where("status",0)->select();
        if (count($codeInfo1)>0){
            return self::bulidFail("验证码发送过于频繁，请稍后再试");
        }
        $codeInfo2 = Db::table("db_smscode")->where("request_ip",$ip)->whereTime("create_time","-1 minutes")->where("status",0)->select();
        if (count($codeInfo2)>0){
            return self::bulidFail("验证码发送过于频繁，请稍后再试");
        }
        $code = rand(100000,999999);
        if (sendCode($mobile,$code,$ip)){
            return self::bulidSuccess();
        }else{
            return self::bulidFail('发送失败');
        }
    }

    public function wxLogin(){
        $code = Request::param("code");
        $wx_data = $this->getWxData($code);
        if (key_exists('errcode', $wx_data)){
            return self::bulidDataFail($wx_data);
        }

        $wx_raw = $this->getUnionId($wx_data["access_token"], $wx_data["openid"]);
        if (key_exists('errcode', $wx_raw)){
            return self::bulidFail("微信授权失败");
        }
        
        //查询是否已注册用户
        $user = UserModel::where('wx_unionid', $wx_raw["unionid"])->with('profile')->field("password, wx_unionid, qq_unionid",true)->find();
        if ($user){
            $user->token = strtoupper(openssl_encrypt(date("YmdHis").rand(10000,99999),"DES-ECB",$this->OPENSSL_KEY));
            $user->last_login = nowFormat();
            $user->login_platform = Request::param('login_platform');
            $user->ip = request()->ip();
            $user->save();
        }else{
            //注册用户
            $gender = $wx_raw["sex"] == 1?1:0;
            $user = new UserModel(["wx_unionid"=>$wx_raw["unionid"], "avatar"=>$wx_raw['headimgurl'], 'nick_name'=>$wx_raw['nickname']]);
            $user->token = strtoupper(openssl_encrypt(date("YmdHis").rand(10000,99999),"DES-ECB",$this->OPENSSL_KEY));
            $user->regist_time = nowFormat();
            $user->last_login = nowFormat();
            $user->login_platform = Request::param('login_platform');
            $user->ip = request()->ip();

            Db::startTrans();
            if ($user->save()){
                $profile = new UserProfileModel(['uid'=>$user->id, 'gender'=>$gender, 'city'=>$wx_raw['province'].$wx_raw['city']]);

                $invite_code = createInviteCode();
                $agent = new AgentModel(['uid'=>$user->id,'profit'=>0,'total_profit'=>0,'invite_code'=>$invite_code]);
                if ($profile->save() && $agent->save()){
                    Db::commit();
                }else{
                    Db::rollback();
                    return self::bulidFail("微信授权失败");
                }
            }else{
                Db::rollback();
                return self::bulidFail("微信授权失败");
            }
        }

        //关注数量
        $user->attent_count = count(getUserAttentAnchorIds($user->id));
        //粉丝数量
        $user->fans_count = getFansCount($user->id);
        //送礼数量
        $user->gift_spend = getSendGiftCount($user->id);
        //访客数量
        $user->visitor_count = getVisitorCount($user->id);
        //守护的主播
        $guardAnchors = AnchorGuardianModel::where(['uid'=>$user->id])->whereTime('expire_time','>',nowFormat())->field('anchorid')->select()->toArray();
        if ($guardAnchors) {
            $user->guard_anchors = array_column($guardAnchors, 'anchorid');
        }

        $configPri = getConfigPri();
        $api = new \Tencent\TLSSigAPIv2($configPri->im_sdkappid, $configPri->im_sdksecret);
        $sig = $api->genSig($user->id);
        $user->txim_sign = $sig;

        $user = $user->toArray();
        if (isset($user['tags'])) {
            //主播形象标签
            $user_tags = explode(',', $user['tags']);
            $tags = ConfigTagModel::where('id', 'in', $user_tags)->select();
            $user['tags'] = $tags;
        }

        return self::bulidSuccess($user);
    }

    public function qqLogin(){
        $unionid = Request::param("unionid");
        $nick_name = Request::param("nick_name");
        $gender = Request::param("gender");
        $icon = Request::param("icon");

        //查询是否已注册用户
        $user = UserModel::where('qq_unionid', $unionid)->with('profile')->field("password, wx_unionid, qq_unionid",true)->find();
        if ($user){
            $user->token = strtoupper(openssl_encrypt(date("YmdHis").rand(10000,99999),"DES-ECB",$this->OPENSSL_KEY));
            $user->last_login = nowFormat();
            $user->login_platform = Request::param('login_platform');
            $user->save();
        }else{
            //注册用户
            $gender = $gender == 1?0:1;
            $user = new UserModel(["qq_unionid"=>$unionid, "avatar"=>$icon, 'nick_name'=>$nick_name]);
            $user->token = strtoupper(openssl_encrypt(date("YmdHis").rand(10000,99999),"DES-ECB",$this->OPENSSL_KEY));
            $user->regist_time = nowFormat();
            $user->last_login = nowFormat();
            $user->login_platform = Request::param('login_platform');

            Db::startTrans();
            if ($user->save()){
                $profile = new UserProfileModel(['uid'=>$user->id, 'gender'=>$gender]);

                $invite_code = createInviteCode();
                $agent = new AgentModel(['uid'=>$user->id,'profit'=>0,'total_profit'=>0,'invite_code'=>$invite_code]);
                if ($profile->save() && $agent->save()){
                    Db::commit();
                }else{
                    Db::rollback();
                    return self::bulidFail("微信授权失败");
                }
            }else{
                Db::rollback();
                return self::bulidFail("微信授权失败");
            }
        }

        //关注数量
        $user->attent_count = count(getUserAttentAnchorIds($user->id));
        //粉丝数量
        $user->fans_count = getFansCount($user->id);
        //送礼数量
        $user->gift_spend = getSendGiftCount($user->id);
        //访客数量
        $user->visitor_count = getVisitorCount($user->id);
        //守护的主播
        $guardAnchors = AnchorGuardianModel::where(['uid'=>$user->id])->whereTime('expire_time','>',nowFormat())->field('anchorid')->select()->toArray();
        if ($guardAnchors) {
            $user->guard_anchors = array_column($guardAnchors, 'anchorid');
        }

        $configPri = getConfigPri();
        $api = new \Tencent\TLSSigAPIv2($configPri->im_sdkappid, $configPri->im_sdksecret);
        $sig = $api->genSig($user->id);
        $user->txim_sign = $sig;

        $user = $user->toArray();
        if ($user['tags']) {
            //主播形象标签
            $user_tags = explode(',', $user['tags']);
            $tags = ConfigTagModel::where('id', 'in', $user_tags)->select();
            $user['tags'] = $tags;
        }

        return self::bulidSuccess($user);
    }

    //粉丝列表
    public function getFans(){
        $page = Request::post("page") ?? 1;
        $size = Request::post("size") ?? 20;

        $list = AnchorFansModel::where(['anchorid'=>$this->userinfo->id])->with('fan')->order('create_time','desc')->limit(($page-1)*$size,$size)->select();
        $fans = [];
        $attentids = getUserAttentAnchorIds($this->userinfo->id);
        foreach ($list as $item){
            foreach ($attentids as $attentid) {
                if ($item->fan->id == $attentid){
                    $item->fan->isattent = 1;
                }
            }
            $fans[] = $item->fan;
        }

        return self::bulidSuccess($fans);
    }

    //获取openid, access_token
    function getWxData($code){
        $config_pri = getConfigPri();
        //返回值：{"access_token":"","expires_in":7200,"refresh_token":"","openid":"","scope":""}
        return json_decode(httpHelper("https://api.weixin.qq.com/sns/oauth2/access_token?appid={$config_pri->wx_appid}&secret={$config_pri->wx_secret}&code={$code}&grant_type=authorization_code",[]),true);
    }

    //获取unionid
    function getUnionId($access_token,$openid){
        $result = httpHelper("https://api.weixin.qq.com/sns/userinfo?access_token={$access_token}&openid={$openid}",[]);
        return json_decode($result,true);
    }

    public function getUserInfo(){
        $token = Request::param("token");
        $uid = Request::param("uid");
        $user = UserModel::where(['id'=>$uid, 'token'=>$token])->with('profile')->field("password, wx_unionid, qq_unionid",true)->find();
        if (!$user){
            return self::bulidLoginTimeOut();
        }

        if (date('Ymd',strtotime($user->last_online)) != date('Ymd')){
            $user->last_online = nowFormat();
            $user->save();
            //增加日活用户数量统计+1
            $platform = Request::param('platform'); //1-ios  2-安卓
            $statistics = StatisticsModel::where(['id'=>intval(date('Ymd'))])->find();
            if (!$statistics){
                $statistics = new StatisticsModel(['id'=>intval(date('Ymd')),'time'=>nowFormat()]);
                $statistics->save();
            }
            if ($statistics){
                if ($platform == 1){
                    $statistics->activity_ios = ['inc',1];
                }elseif ($platform == 2){
                    $statistics->activity_android = ['inc',1];
                }
                $statistics->save();
            }
        }

        //关注数量
        $user->attent_count = count(getUserAttentAnchorIds($uid));
        //粉丝数量
        $user->fans_count = getFansCount($uid);
        //送礼数量
        $user->gift_spend = getSendGiftCount($uid);
        //访客数量
        $user->visitor_count = getVisitorCount($user->id);
        //守护的主播
        $guardAnchors = AnchorGuardianModel::where(['uid'=>$uid])->whereTime('expire_time','>',nowFormat())->field('anchorid')->select()->toArray();
        if ($guardAnchors) {
            $user->guard_anchors = array_column($guardAnchors, 'anchorid');
        }

        $configPri = getConfigPri();
        $api = new \Tencent\TLSSigAPIv2($configPri->im_sdkappid, $configPri->im_sdksecret);
        $sig = $api->genSig($user->id);
        $user->txim_sign = $sig;

        $user = $user->toArray();
        if ($user['tags']) {
            //主播形象标签
            $user_tags = explode(',', $user['tags']);
            $tags = ConfigTagModel::where('id', 'in', $user_tags)->select();
            $user['tags'] = $tags;
        }
        $user['invite_code'] = AgentModel::where(['uid'=>$user['id']])->value('invite_code');// 邀请码
        return self::bulidSuccess($user);
    }

    public function getUserBasicInfo(){
        $id = Request::param("id");
        $user = UserModel::where(['id'=>$id])->with('profile')->field("id, nick_name, avatar, is_anchor, vip_date, vip_level, user_level, anchor_level")->find();
        if (!$user) {
            return self::bulidFail('用户状态异常');
        }

        //关注数量
        $user->attent_count = count(getUserAttentAnchorIds($id));
        //粉丝数量
        $user->fans_count = getFansCount($id);
        //送礼数量
        $user->gift_spend = getSendGiftCount($id);
        //访客数量
        $user->visitor_count = getVisitorCount($user->id);
        //守护的主播
        $guardAnchors = AnchorGuardianModel::where(['uid'=>$user->id])->whereTime('expire_time','>',nowFormat())->field('anchorid')->select()->toArray();
        if ($guardAnchors) {
            $user->guard_anchors = array_column($guardAnchors, 'anchorid');
        }

        if ($uid = Request::param('uid')){
            //查询是否已关注
            $checkAttent = AnchorFansModel::where(['anchorid' => $id, 'fansid' => Request::post("uid")])->find();
            if ($checkAttent) {
                $user['isattent'] = 1;
            } else {
                $user['isattent'] = 0;
            }
        }

        return self::bulidSuccess($user);
    }

    public function editUserInfo(){
        $avatar = Request::post('avatar');
        $nickName = Request::post('nick_name');

        $signature = Request::post('signature');
        $age = Request::post('age');
        $gender = Request::post('gender');
        $height = Request::post('height');
        $weight = Request::post('weight');
        $career = Request::post('career');
        $constellation = Request::post('constellation');
        $city = Request::post('city');
        $photos = Request::post('photos');
        if (!$avatar && !$nickName && !$signature && !$age && !$gender && !$height && !$weight && !$career && !$constellation && !$city && !$photos){
            return self::bulidSuccess();
        }
        $user_condition = [];
        if ($avatar){
            $user_condition['avatar'] = $avatar;
        }
        if ($nickName){
            $user_condition['nick_name'] = $nickName;
        }
        $profile_condition = [];
        if ($signature){
            $profile_condition['signature'] = $signature;
        }
        if ($age){
            $profile_condition['age'] = $age;
        }
        if ($gender){
            $profile_condition['gender'] = $gender;
        }
        if ($height){
            $profile_condition['height'] = $height;
        }
        if ($weight){
            $profile_condition['weight'] = $weight;
        }
        if ($career){
            $profile_condition['career'] = $career;
        }
        if ($constellation){
            $profile_condition['constellation'] = $constellation;
        }
        if ($city){
            $profile_condition['city'] = $city;
        }
        if ($photos){
            $profile_condition['photos'] = $photos;
        }
        Db::startTrans();
        if ($this->userinfo->save($user_condition) && UserProfileModel::update($profile_condition,['uid'=>$this->userinfo->id])){
            Db::commit();
            return self::bulidSuccess();
        }
        Db::rollback();
        return self::bulidFail();
    }

    public function bindPhone(){
        $account = Request::param("mobile");
        $password = openssl_encrypt(substr($account,strlen($account)-4,4).Request::param("pwd"),"DES-ECB",$this->OPENSSL_KEY);

        //验证手机号
        $usercheck = UserModel::where("account",$account)->find();
        if ($usercheck){
            return self::bulidFail("手机号已被注册");
        }
        //验证码
        $codeinfo = SmscodeModel::where("mobile",$account)->where("code",Request::param("smscode"))->where("status",0)->whereTime("create_time","-30 minutes")->order("create_time","desc")->find();
        if (!$codeinfo){
            return self::bulidFail("验证码有误或已过期");
        }
        $codeinfo->status = 1;
        $codeinfo->save();

        $user = $this->userinfo;
        $user->account = $account;
        $user->password = $password;

        $userpoint = $user->point + addUserPointEventBindMobile();
        $user->point = ['inc', addUserPointEventBindMobile()];
        //更新用户等级
        $user->user_level = calculateUserLevel($userpoint);

        if ($user->save()){
            return self::bulidSuccess();
        }
        return self::bulidFail();
    }

    public function changePwd(){
        $account = Request::param("mobile");
        $password = openssl_encrypt(substr($account,strlen($account)-4,4).Request::param("pwd"),"DES-ECB",$this->OPENSSL_KEY);

        //验证手机号
        $user = UserModel::where("account",$account)->find();
        if (!$user){
            return self::bulidFail("该手机号尚未注册");
        }
        //验证码
        $codeinfo = SmscodeModel::where("mobile",$account)->where("code",Request::param("smscode"))->where("status",0)->whereTime("create_time","-30 minutes")->order("create_time","desc")->find();
        if (!$codeinfo){
            return self::bulidFail("验证码有误或已过期");
        }
        $codeinfo->status = 1;
        $codeinfo->save();

        $user->password = $password;
        if ($user->save()){
            return self::bulidSuccess();
        }
        return self::bulidFail();
    }

    public function userAuthInfo(){
        $auth = UserAuthModel::where(['uid'=>$this->userinfo->id,'status'=>1])->find();
        if ($auth)
            return self::bulidSuccess($auth);
        return self::bulidFail('尚未通过身份认证');
     }

    public function giftProfit(){
        $page = Request::post("page") ?? 1;
        $size = Request::post("size") ?? 20;
        $profit = UserProfitModel::where(['uid'=>$this->userinfo->id,'consume_type'=>1,'type'=>1])->with('gift')->limit(($page-1)*$size,$size)->order('create_time','desc')->select();
        return self::bulidSuccess($profit);
    }

    public function momentProfit(){
        $page = Request::post("page") ?? 1;
        $size = Request::post("size") ?? 20;
        $profit = UserProfitModel::where(['uid'=>$this->userinfo->id,'consume_type'=>2,'type'=>1])->with('moment')->limit(($page-1)*$size,$size)->order('create_time','desc')->select();
        return self::bulidSuccess($profit);
    }

    public function getLiveLog(){
        $page = Request::post("page") ?? 1;
        $size = Request::post("size") ?? 20;
        $lives = LiveHistoryModel::where(['anchorid'=>$this->userinfo->id])->limit(($page-1)*$size,$size)->order(["start_time desc"])->select();
        return self::bulidSuccess($lives);
    }

    public function myMoment(){
        $status = Request::param('status') ?? 1;
        $page = Request::post("page") ?? 1;
        $size = Request::post("size") ?? 20;
        $moments = MomentModel::where(['uid'=>$this->userinfo->id,'status'=>$status])->with('user')->limit(($page-1)*$size,$size)->order(["create_time desc"])->select();
        foreach ($moments as $moment){
            $moment->unlocked = 1;

            $likedids = getUserLikeMomentIds($this->userinfo->id);
            foreach ($likedids as $likedid) {
                foreach ($moments as $moment) {
                    if ($moment->id == $likedid) {
                        $moment->liked = 1;
                    }
                }
            }
        }
        return self::bulidSuccess($moments);
    }

    public function myVideo(){
        $status = Request::param('status') ?? 1;
        $page = Request::post("page") ?? 1;
        $size = Request::post("size") ?? 20;
        $videos = ShortvideoModel::where(['status'=>$status,'uid'=>$this->userinfo->id])->with(['author'])->order('create_time','desc')->limit($size*($page-1),$size)->select();
        foreach ($videos as $video){
            $video->unlocked = 1;

            $likedids = getUserLikeShortVideoIds($this->userinfo->id);
            foreach ($likedids as $likedid){
                foreach ($videos as $index=>$video){
                    if ($video->id == $likedid){
                        $video->is_like = 1;
                    }
                }
            }
        }
        return self::bulidSuccess($videos);
    }

    public function getUserLevelInfo(){
        $level = UserLevelRuleModel::where(['level'=>$this->userinfo->user_level])->find();
        $levelnext = UserLevelRuleModel::where(['level'=>$this->userinfo->user_level + 1])->find();
        return self::bulidSuccess(['level'=>$level, 'next'=>$levelnext]);
    }

    public function getManagedRooms(){
        $page = Request::param('page') ?? 1;
        $size = Request::param('size') ?? 20;
        $mgrs = LiveRoomManagerModel::where(['mgrid'=>$this->userinfo->id])->with('anchor')->limit(($page-1)*$size,$size)->select();
        $anchors = [];
        foreach ($mgrs as $mgr){
            $anchors[] = $mgr->anchor;
        }
        return self::bulidSuccess($anchors);
    }

    public function getTempUserKey(){
        $tempuid = Request::param('temp_uid');
        $configPri = getConfigPri();
        $api = new \Tencent\TLSSigAPIv2($configPri->im_sdkappid, $configPri->im_sdksecret);
        $sig = $api->genSig($tempuid);
        return self::bulidSuccess(['txim_sign'=>$sig]);
    }

    // 绑定代理
    public function bindAgent(){
        $post = Request::post();
        $invite_code = $post['invite_code'];
        if(empty($invite_code)){
            return self::bulidFail('请输入邀请码');    
        }
        // 验证当前用户是否一绑定
        if(!empty($this->userinfo->agentid)){
            return self::bulidFail('当前用户已绑定代理，请勿重复操作');    
        }
        $parent = (new AgentModel())->where(['invite_code'=>$invite_code])->find();
        if(!$parent){
            return self::bulidFail('邀请码错误，请仔细核对');
        }
        $invite_id = $parent['uid'];
        if($invite_id == $this->userinfo->id){
            return self::bulidFail('绑定失败，不能绑定自己');
        }    
        Db::startTrans();
        try {
            //$parent->save();
            /*// 保存parentid
            (new AgentModel())->where(['uid'=>$this->userinfo->id])->update(['parentid'=>$invite_id]);*/

            $this->userinfo->save(['agentid'=>$invite_id]);
            Db::commit();
            return self::bulidSuccess('','ok');
        } catch (\Exception $e) {
            Db::rollback();
            return self::bulidFail($e->getMessage());
        }
    }

    // 手机号登录
    public function phoneLogin(){
        $account = Request::param("account");
        $login_platform = Request::param('platform');
        //验证码
        $codeinfo = SmscodeModel::where("mobile",$account)->where("code",Request::param("smscode"))->where("status",0)->whereTime("create_time","-30 minutes")->order("create_time","desc")->find();
        if (!$codeinfo){
            return self::bulidFail("验证码有误或已过期");
        }
        $codeinfo->status = 1;
        $codeinfo->save();
        // 检测是否已注册
        $user = UserModel::where("account",$account)->with('profile')->field("password, wx_unionid, qq_unionid",true)->find();
        if($user){
            if ($user->status == 1){
                return self::bulidFail("账号状态异常");
            }
            $user->token = strtoupper(openssl_encrypt(date("YmdHis").rand(10000,99999),"DES-ECB",$this->OPENSSL_KEY));
            $user->last_login = nowFormat();
            $user->login_platform = $login_platform;
            $user->save();
            //关注数量
            $user->attent_count = count(getUserAttentAnchorIds($user->id));
            //粉丝数量
            $user->fans_count = getFansCount($user->id);
            //送礼数量
            $user->gift_spend = getSendGiftCount($user->id);
            //访客数量
            $user->visitor_count = getVisitorCount($user->id);
            //守护的主播
            $guardAnchors = AnchorGuardianModel::where(['uid'=>$user->id])->whereTime('expire_time','>',nowFormat())->field('anchorid')->select()->toArray();
            if ($guardAnchors) {
                $user->guard_anchors = array_column($guardAnchors, 'anchorid');
            }
            $configPri = getConfigPri();
            $api = new \Tencent\TLSSigAPIv2($configPri->im_sdkappid, $configPri->im_sdksecret);
            $sig = $api->genSig($user->id);
            $user->txim_sign = $sig;
            $user = $user->toArray();
            if ($user['tags']){
                //主播形象标签
                $user_tags = explode(',',$user['tags']);
                $tags = ConfigTagModel::where('id', 'in', $user_tags)->select();
                $user['tags'] = $tags;
            }
            return self::bulidSuccess($user);
        }else{
            $user = new UserModel();
            $point = addUserPointEventBindMobile();
            $nick_name = '手机用户'.substr($account,7,4);
            $token = strtoupper(openssl_encrypt(date("YmdHis").rand(10000,99999),"DES-ECB",$this->OPENSSL_KEY));
            $add_data = [
                'account' => $account,
                'nick_name' => $nick_name,
                'token' => $token,
                'regist_time' => nowFormat(),
                'last_login' => nowFormat(),
                'login_platform' => $login_platform,
                'point' => $point,
                'user_level' => calculateUserLevel($point)
            ];
            if ($invite_code = Request::param('invite_code')){
                $agent_id = AgentModel::where(['invite_code'=>$invite_code])->value('uid');
                if ($agent_id){
                    $add_data['agentid'] = $agent_id;
                }
            }
            Db::startTrans();
            try {
                $user->save($add_data);
                //统计注册用户数+1
                $platform = Request::param('platform'); //1-ios  2-安卓
                $statistics = StatisticsModel::where(['id'=>intval(date('Ymd'))])->find() ?: new StatisticsModel(['id'=>intval(date('Ymd')),'time'=>nowFormat()]);
                if ($platform == 1){
                    $statistics->regist_ios = ['inc',1];
                }elseif ($platform == 2){
                    $statistics->regist_android = ['inc',1];
                }
                $statistics->save();
                // 用户补充信息
                $profile = new UserProfileModel(['uid'=>$user->id]);
                $profile->save();
                // 代理信息
                $invite_code = createInviteCode();
                $agent = new AgentModel(['uid'=>$user->id,'profit'=>0,'total_profit'=>0,'invite_code'=>$invite_code]);
                $agent->save();
                $configPri = getConfigPri();
                $api = new \Tencent\TLSSigAPIv2($configPri->im_sdkappid, $configPri->im_sdksecret);
                $sig = $api->genSig($user->id);
                $user->txim_sign = $sig;
                $user->profile = $profile;
                Db::commit();
                return self::bulidSuccess($user);
            }catch(\Exception $e){
                Db::rollback();
                return self::bulidFail("注册失败");
            }
        } 
    }
}