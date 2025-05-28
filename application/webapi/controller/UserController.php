<?php


namespace app\webapi\controller;


use app\common\model\AgentModel;
use app\common\model\AnchorFansModel;
use app\common\model\ConfigTagModel;
use app\common\model\LiveHistoryModel;
use app\common\model\LiveRoomManagerModel;
use app\common\model\MomentModel;
use app\common\model\SmscodeModel;
use app\common\model\StatisticsModel;
use app\common\model\UserModel;
use app\common\model\UserProfileModel;
use app\common\model\UserProfitModel;
use think\Db;
use think\facade\Request;

class UserController extends BaseController
{
    protected $NeedLogin = ['getUserInfo', 'editUserInfo','bindPhone','giftProfit','momentProfit','userAuthInfo','sendVerifyCode','getLiveLog','getManagedRooms'];

    protected $rules = array(
        'login'=>array(
            'account'=>'require|length:6,18',
            'pwd'=>'require|length:6,18'
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
        $user->login_platform = 0;
        $user->save();

        //关注数量
        $user->attent_count = count(getUserAttentAnchorIds($user->id));
        //粉丝数量
        $user->fans_count = getFansCount($user->id);
        //送礼数量
        $user->gift_spend = getSendGiftCount($user->id);
        //访客数量
        $user->visitor_count = getVisitorCount($user->id);

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
        $user->login_platform = 0;

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
            $platform = 3; //1-ios  2-安卓  3-PC
            $statistics = StatisticsModel::where(['id'=>intval(date('Ymd'))])->find();
            if (!$statistics){
                $statistics = new StatisticsModel(['id'=>intval(date('Ymd')),'time'=>nowFormat()]);
                $statistics->save();
            }
            if ($statistics){
                $statistics->regist_pc = ['inc',1];
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

        if (sendCode($mobile,$code,$ip)){
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
        //动态数量
        $user->moment_count = MomentModel::where(['uid'=>$uid,'status'=>1])->count("id");

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
        if (!$avatar && !$nickName && !$signature && !$age && !$gender && !$height && !$weight && !$career && !$constellation && !$city){
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
        Db::startTrans();
        if ($this->userinfo->save($user_condition) && UserProfileModel::update($profile_condition,['uid'=>$this->userinfo->id])){
            Db::commit();
            return self::bulidSuccess();
        }
        Db::rollback();
        return self::bulidFail();
    }

    public function giftProfit(){
        $page = Request::post("page") ?? 1;
        $size = Request::post("size") ?? 20;
        $profit = UserProfitModel::where(['uid'=>$this->userinfo->id,'consume_type'=>1,'type'=>1])->with('gift')->limit(($page-1)*$size,$size)->order('create_time','desc')->select();
        $count = UserProfitModel::where(['uid'=>$this->userinfo->id,'consume_type'=>1,'type'=>1])->count('id');
        return self::bulidSuccess(['list'=>$profit,'count'=>$count]);
    }

    public function momentProfit(){
        $page = Request::post("page") ?? 1;
        $size = Request::post("size") ?? 20;
        $profit = UserProfitModel::where(['uid'=>$this->userinfo->id,'consume_type'=>2,'type'=>1])->with('moment')->limit(($page-1)*$size,$size)->order('create_time','desc')->select();
        $count = UserProfitModel::where(['uid'=>$this->userinfo->id,'consume_type'=>2,'type'=>1])->count('id');
        return self::bulidSuccess(['list'=>$profit,'count'=>$count]);
    }

    public function getLiveLog(){
        $page = Request::post("page") ?? 1;
        $size = Request::post("size") ?? 20;
        $lives = LiveHistoryModel::where(['anchorid'=>$this->userinfo->id])->limit(($page-1)*$size,$size)->order(["start_time desc"])->select();
        $count = LiveHistoryModel::where(['anchorid'=>$this->userinfo->id])->count('id');
        return self::bulidSuccess(['list'=>$lives,'count'=>$count]);
    }

    public function getManagedRooms(){
        $page = Request::param('page') ?? 1;
        $size = Request::param('size') ?? 20;
        $mgrs = LiveRoomManagerModel::where(['mgrid'=>$this->userinfo->id])->with('anchor')->limit(($page-1)*$size,$size)->select();
        $anchors = [];
        foreach ($mgrs as $mgr){
            $anchors[] = $mgr->anchor;
        }
        $count = LiveRoomManagerModel::where(['mgrid'=>$this->userinfo->id])->count("id");
        return self::bulidSuccess(['rooms'=>$anchors,'count'=>$count]);
    }

    // 微信登录
    public function wxLogin(){
        $appid=$settings['wxapi_m'];
        $appsecret=$settings['appsecret_m'];
        $url=$settings['redirecturi_m'];
        $code = Request::param('code');
        //获取网页授权的access_token
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$appid."&secret=".$appsecret."&code=".$code."&grant_type=authorization_code";
        //请求 $url 返回一个json    json_decode不加 true 会将json转为对象，加true转为数组
        $res = json_decode($this->get_by_curl($url),true);
        //获取access_token
        $access_token = $res["access_token"];
        //获取openid
        $openid = $res["openid"];
        //拼接字符串并赋值给 $urls
        $urls = "https://api.weixin.qq.com/sns/userinfo?access_token=".$access_token."&openid=".$openid."&lang=zh_CN";
        //请求用户详细信息，并赋值给 $userinfo
        $userinfo = $this->get_by_curl($urls);
        $userinfos=json_decode($userinfo, true);
        // dump($userinfos);die;
        $data['unionid']=$userinfos['unionid'];//和微信开发平台绑定后会有个唯一的unionid
        $data['figureurl_qq']=$userinfos['headimgurl'];
        $data['openid']=$userinfos['openid'];
        $data['name']=$userinfos['nickname'];
        $data['time']=time();
        $data['sex']=$userinfos['sex'];
        
    }

    function get_by_curl($url,$post = false){
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if($post){
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,$post);
        }
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}