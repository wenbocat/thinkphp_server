<?php
/**
 * Created by PhpStorm.
 * User: yun7_mac
 * Date: 2019-12-13
 * Time: 17:17
 */

namespace app\api\controller;


use app\common\model\AnchorFansModel;
use app\common\model\AnchorGuardianModel;
use app\common\model\AnchorIntimacyModel;
use app\common\model\AnchorReportModel;
use app\common\model\ConfigTagModel;
use app\common\model\ConnectModel;
use app\common\model\GiftLogModel;
use app\common\model\IntimacyModel;
use app\common\model\LiveModel;
use app\common\model\UserConsumeModel;
use app\common\model\UserModel;
use app\common\model\UserProfitModel;
use app\common\model\VisitorLogModel;
use app\common\TXService;
use think\Db;
use think\facade\Request;

class AnchorController extends BaseController
{
    protected $NeedLogin = ['getAttentAnchors', 'attentAnchor', 'addVisitorLog','checkAttent','report','guard','getGuardInfo'];

    protected $rules = array(

        //关注主播列表
        'getattentanchors'=>array(
        ),

        //主播详情
        'getanchorinfo'=>array(
            'anchorid'=>'require'
        ),

        //主播基础信息
        'getanchorbasicinfo'=>array(
            'anchorid'=>'require'
        ),

        //用户评价列表
        'getuserevaluates'=>array(
            'anchorid'=>'require'
        ),

        //关注、取消关注主播
        'attentanchor'=>array(
            'anchorid'=>'require',
            'type'=>'require' //1关注 0-取消
        ),
        //写入访客记录
        'addvisitorlog'=>array(
            'anchorid'=>'require'
        ),
        //检测是否已关注
        'checkattent'=>[
            'anchorid'=>'require'
        ],

        //举报
        'report'=>[
            'relateid'=>'require',
            'title'=>'require',
            'content'=>'require',
            'img_urls'=>'require'
        ],

        //守护
        'guard'=>[
            'anchorid'=>'require',
            'type'=>'require',//0-年 1-月 2-周
        ],

        'getguardinfo'=>[
            'anchorid'=>'require',
        ],

        //搜索
        'search'=>[
            'keyword'=>'require'
        ]
    );

    public function initialize()
    {
        parent::initialize();
    }

    //关注主播列表
    public function getAttentAnchors(){
        $page = Request::post("page") ?? 1;
        $size = Request::post("size") ?? 20;

        $attents = AnchorFansModel::where(['fansid'=>$this->userinfo->id])->with('anchor')->order('create_time','desc')->limit(($page-1)*$size,$size)->select();
        $anchors = [];
        foreach ($attents as $index=>$attent){
            if ($attent->anchor)
                $attent->anchor->isattent = 1;
            $anchors[] = $attent->anchor;
        }

        return self::bulidSuccess($anchors);
    }

    public function search(){
        $page = Request::post("page") ?? 1;
        $size = Request::post("size") ?? 20;
        $keyword = Request::post("keyword");
        $anchors = UserModel::whereRaw("nick_name like '%{$keyword}%'")->where('status', 0)->order(['online_status'=>'asc', 'rec_weight'=>'desc', 'anchor_point'=>'desc', 'regist_time'=>'desc'])->with('profile')->field(["id, nick_name, avatar, user_level, anchor_level, online_status"])->limit($size*($page-1),$size)->select();

        if ($uid = Request::post("uid")) {
            $attentids = getUserAttentAnchorIds($uid);
            foreach ($attentids as $attentid) {
                foreach ($anchors as $index => $anchor) {
                    if ($anchor->id == $attentid) {
                        $anchor->isattent = 1;
                    }
                }
            }
        }

        return self::bulidSuccess($anchors);
    }

    //主播详情
    public function getAnchorInfo(){
        $anchorid = Request::post("anchorid");
        $uid = Request::post("uid");
        $anchor = UserModel::where(['id'=>$anchorid, 'status'=>0])->with(['live','profile'])->field(['password','token','wx_unionid'],true)->find();
        if (!$anchor){
            return self::bulidFail("主播不存在或账号状态异常");
        }else{
            if ($this->userinfo){
                //写入访客记录
                $visitorlog = new VisitorLogModel(['uid'=>$anchorid, 'visitorid'=>$this->userinfo->id, 'create_time'=>nowFormat()]);
                if($visitorlog->save()) {
                    $visitorCount = VisitorLogModel::where('uid', $anchorid)->count();
                    setVisitorCount($anchorid, $visitorCount);
                }
            }

            $anchor = $anchor->toArray();
            if ($uid){
                //查询是否已关注
                $checkAttent = AnchorFansModel::where(['anchorid' => $anchorid, 'fansid' => Request::post("uid")])->find();
                if ($checkAttent) {
                    $anchor['isattent'] = 1;
                } else {
                    $anchor['isattent'] = 0;
                }
            }
            if ($anchor['tags']){
                //主播形象标签
                $anchor_tags = explode(',',$anchor['tags']);
                $tags = ConfigTagModel::where('id', 'in', $anchor_tags)->select();
                $anchor['tags'] = $tags;
            }
            //主播接通率 1v1
            $connect_rate = '100%';
            if ($anchor["call_recive_count"] > 0){
                $connect_rate = number_format($anchor["call_accept_count"]/$anchor["call_recive_count"], 2)*100 . '%';
            }
            $anchor['connect_rate'] = $connect_rate;

            //关注数量
            $anchor['attent_count'] = count(getUserAttentAnchorIds($anchorid));
            //粉丝数量
            $anchor['fans_count'] = getFansCount($anchorid);
            //送礼数量
            $anchor['gift_spend'] = getSendGiftCount($anchorid);

            //礼物展示
            $gift_recive = GiftLogModel::where('anchorid', $anchorid)->with(['gift'])->order('spend desc')->limit(0,5)->select();
            foreach ($gift_recive as $gift){
                $anchor['gift_show'][] = $gift->gift;
            }

            //亲密榜展示
            $intimacys = IntimacyModel::where('anchorid', $anchorid)->with(['user'=>function($query){
                $query->field(['id','nick_name','avatar']);
            }])->order('intimacy desc')->limit(0,5)->select();
            $anchor['intimacys'] = $intimacys;

            return self::bulidSuccess($anchor);
        }
    }

    //主播基础信息
    public function getAnchorBasicInfo(){
        $anchorid = Request::post("anchorid");
        $anchor = UserModel::where(['id'=>$anchorid, 'status'=>0])->with('profile')->field(['id','nick_name','avatar','diamond_total','rec_weight','anchor_level','is_anchor','anchor_point'])->find();
        //粉丝数量
        $anchor->fans_count = getFansCount($anchorid);
        if ($uid = Request::param('uid')){
            $checkAttent = AnchorFansModel::where(['anchorid' => $anchorid, 'fansid' => Request::post("uid")])->find();
            if ($checkAttent) {
                $anchor['isattent'] = 1;
            } else {
                $anchor['isattent'] = 0;
            }
        }
        if (!$anchor){
            return self::bulidFail("主播不存在或账号状态异常");
        }else{
            return self::bulidSuccess($anchor);
        }
    }

    //用户评价列表
    public function getUserEvaluates(){
        $anchorid = Request::post("anchorid");

        $page = Request::post("page");
        $size = Request::post("size");
        if (!$page){
            $page = 1;
        }
        if (!$size){
            $size = 10;
        }

        $connects = ConnectModel::where(['anchorid'=>$anchorid , "eval_status"=>1])->with(['user' => function($query){
            $query->field(['id', 'avatar', 'nick_name']);
        }, 'evaluate'])->order('create_time desc')->limit(($page-1)*$size,$size)->select();

        $evaluates = [];
        foreach ($connects as $connect){
            $evaluate["connectid"] = $connect->id;
            $evaluate['user']["id"] = $connect->user->id;
            $evaluate['user']["avatar"] = $connect->user->avatar;
            $evaluate['user']["nick_name"] = $connect->user->nick_name;
            $evaluate['evaluate'] = $connect->evaluate;
            $evaluates[] = $evaluate;
        }

        return self::bulidSuccess($evaluates);
    }

    //关注、取消关注主播
    public function attentAnchor(){
        $anchorid = Request::post("anchorid");
        if ($anchorid == $this->userinfo->id){
            return self::bulidFail("无法关注自己");
        }
        $anchor = UserModel::where(['id'=>$anchorid])->find();
        if (!$anchor){
            return self::bulidFail('主播账户状态异常');
        }
        $type = Request::post("type");
        $attent = AnchorFansModel::where(['fansid'=>$this->userinfo->id, 'anchorid'=>$anchorid])->find();
        if ($attent && $type == 0){
            //取消关注
            if ($attent->delete()){
                //更新redis缓存
                $attentids = getUserAttentAnchorIds($this->userinfo->id);
                if ($attentids && in_array($anchorid, $attentids)){
                    $attentids = array_diff($attentids, [$anchorid]);
                }
                setUserAttentAnchorIds($this->userinfo->id, $attentids);

                $fans_count = AnchorFansModel::where('anchorid', $anchorid)->count();
                setFansCount($anchorid, $fans_count);
                return self::bulidSuccess(['fans_count'=>$fans_count]);
            }
        }else if ($attent){
            $fans_count = AnchorFansModel::where('anchorid', $anchorid)->count();
            setFansCount($anchorid, $fans_count);
            return self::bulidSuccess(['fans_count'=>$fans_count]);
        }else{
            $attent = new AnchorFansModel(['fansid'=>$this->userinfo->id, 'anchorid'=>$anchorid, 'create_time'=>nowFormat()]);
            //关注
            if ($attent->save()){
                //更新redis缓存
                $attentids = getUserAttentAnchorIds($this->userinfo->id);
                if ($attentids && !in_array($anchorid,$attentids)){
                    $attentids[] = $anchorid;
                }
                setUserAttentAnchorIds($this->userinfo->id, $attentids);

                $fans_count = AnchorFansModel::where('anchorid', $anchorid)->count();
                setFansCount($anchorid, $fans_count);
                return self::bulidSuccess(['fans_count'=>$fans_count]);
            }
        }
        return self::bulidFail();
    }

    public function addVisitorLog(){
        $anchorid = Request::post("anchorid");
        //写入访客记录
        $visitorlog = new VisitorLogModel(['uid'=>$anchorid, 'visitorid'=>$this->userinfo->id, 'create_time'=>nowFormat()]);
        if($visitorlog->save()){
            $visitorCount = VisitorLogModel::where('uid', $anchorid)->count();
            setVisitorCount($anchorid,$visitorCount);
            return self::bulidSuccess([]);
        }
        return self::bulidFail();
    }

    public function checkAttent(){
        $anchorid = Request::post("anchorid");
        $attend = AnchorFansModel::where(['fansid'=>$this->userinfo->id,'anchorid'=>$anchorid])->find();
        if ($attend){
            return self::bulidSuccess(['attented'=>1]);
        }else{
            return self::bulidSuccess(['attented'=>0]);
        }
    }

    public function reoprt(){
        $model = new AnchorReportModel(Request::param());
        $model->anchorid = Request::param('relateid');
        $model->create_time = nowFormat();
        if ($model->save()){
            return self::bulidSuccess();
        }
        return self::bulidFail();
    }

    //守护主播
    public function guard(){
        $configGuard = getConfigGuard();
        $anchorid = Request::param('anchorid');
        $renew = Request::param('renew') ?? 0;
        $type = Request::param('type');

        switch ($type){
            case 0:
                $price = $configGuard->year_price;
                $duration = 365*24*60*60;
                $duration_str = '一年';
                break;
            case 1:
                $price = $configGuard->month_price;
                $duration = 30*24*60*60;
                $duration_str = '一月';
                break;
            case 2:
                $price = $configGuard->week_price;
                $duration = 7*24*60*60;
                $duration_str = '一周';
                break;
            default:
                $price = 0;
                $duration = 0;
                break;
        }
        if ($price == 0){
            return self::bulidFail();
        }
        if ($renew){
            $price = ceil($price * 0.9);
        }
        if ($price > $this->userinfo->gold){
            return self::bulidChargeFail();
        }

        $usergoldleft = $this->userinfo->gold - $price;

        $anchor = UserModel::where(['id'=>$anchorid])->field(['id,nick_name','online_status','sharing_ratio','guildid','anchor_point'])->find();
        if (!$anchor){
            return self::bulidFail('主播信息有误');
        }

        $guardLog = AnchorGuardianModel::where(['anchorid'=>$anchorid,'uid'=>$this->userinfo->id])->find();
        if ($guardLog && strtotime($guardLog->expire_time) > time()){
            $expire_time = strtotime($guardLog->expire_time) + $duration;
            $guardLog->expire_time = date('Y-m-d H:i:s',$expire_time);
        }else{
            $expire_time = time() + $duration;
            if (!$guardLog){
                $guardLog = new AnchorGuardianModel(['anchorid'=>$anchorid,'uid'=>$this->userinfo->id,'effective_time'=>nowFormat()]);
            }
            $guardLog->expire_time = date('Y-m-d H:i:s',$expire_time);
        }
        $guardLog->type = $type;

        Db::startTrans();

        if ($guardLog->save()){
//            //金币钻石结算
            $user = $this->userinfo;

            //扣除用户金币
            $user->gold = ['dec', $price];
            //增加用户经验值
            $userpoint = $user->point + addUserPointEventSendGift($price);
            $user->point = ['inc', addUserPointEventSendGift($price)];
            //更新用户等级
            $user->user_level = calculateUserLevel($userpoint);

            //增加主播钻石
            $anchor_diamond_get = round($price * $anchor->sharing_ratio / 100);
            $anchor->diamond = ['inc', $anchor_diamond_get];
            $anchor->diamond_total = ['inc', $anchor_diamond_get];
            //增加主播经验值
            $anchorpoint = $anchor->anchor_point + $anchor_diamond_get;
            $anchor->anchor_point = ['inc',$anchor_diamond_get];
            //更新主播等级
            $anchor->anchor_level = calculateUserLevel($anchorpoint);

            //写入主播收益记录
            $anchor_profit = new UserProfitModel(['uid'=>$anchor->id, 'coin_count'=>$anchor_diamond_get, 'content'=>"用户{$user->nick_name}(ID:{$user->id}) 守护{$duration_str}，收入：{$anchor_diamond_get}钻石", 'type'=>1, 'consume_type'=>3, 'resid'=>0,'create_time'=>nowFormat()]);
            //写入用户消费记录
            $user_profit = new UserProfitModel(['uid'=>$user->id, 'coin_count'=>$price, 'content'=>"守护主播({$anchor->nick_name} ID:{$anchor->id}){$duration_str}", 'type'=>0, 'consume_type'=>3, 'resid'=>0, 'create_time'=>nowFormat()]);

            //增加亲密度
            $intimacy = IntimacyModel::where(['anchorid'=>$anchor->id,'uid'=>$user->id])->find();
            if ($intimacy){
                $intimacy->intimacy = ['inc', $price];
                $intimacy->intimacy_week = ['inc', $price];
            }else{
                $intimacy = new IntimacyModel();
                $intimacy->uid = $user->id;
                $intimacy->anchorid = $anchor->id;
                $intimacy->intimacy = $price;
                $intimacy->intimacy_week = $price;
            }

            //写入用户当日消费统计 用于排行榜
            $userConsume = UserConsumeModel::where(['uid'=>$user->id])->whereTime('date','today')->find();
            if (!$userConsume){
                $userConsume = new UserConsumeModel(['uid'=>$user->id,'consume'=>0,'date'=>date('Y-m-d')]);
            }
            $userConsume->consume = ['inc',$price];

            //写入直播收益
            $live_profit_update = 1;
            $live = LiveModel::where(['anchorid'=>$anchorid])->find();
            if ($live){
                $live_profit_update = Db::table('db_live')->where('liveid',$live->liveid)->inc(['profit'=>$price,'hot'=>$price*10])->update();
            }

            if($anchor->save() && $anchor_profit->save() && $user->save() && $user_profit->save() && $intimacy->save() && $userConsume->save() && $live_profit_update){
                Db::commit();
                self::echoSuccess(['gold'=>$usergoldleft,'guard'=>$guardLog]);
                if ($live){
                    //通过IM推送到直播间
                    $im_ext['notify'] = ['type'=>'RoomNotifyTypeGuardAnchor','user'=>$user->visible(['id','nick_name','user_level','avatar'])];
                    $elem = TXService::buildCustomElem('RoomNotification',$im_ext);
                    TXService::sendChatRoomMsg(getAnchorRoomID($anchorid),$elem);
                }
                exit();
            }else{
                Db::rollback();
                return self::bulidCodeFail(1001,"赠送失败");
            }
        }else{
            Db::rollback();
            return self::bulidFail();
        }
    }

    public function getGuardInfo(){
        $guard = AnchorGuardianModel::where(['anchorid'=>Request::param('anchorid'),'uid'=>$this->userinfo->id])->find();
        if ($guard) {
            return self::bulidSuccess($guard);
        }
        return self::bulidSuccess(null);
    }

    public function getGuardianCount(){
        $count = AnchorGuardianModel::where(['anchorid'=>Request::param('anchorid')])->whereTime('expire_time','>',nowFormat())->count('id');
        return self::bulidSuccess(['count'=>$count]);
    }

    public function getGuardianList(){
        $now = nowFormat();
        $anchorid = Request::param('anchorid');
        $page = Request::param('page') ?? 1;
        $size = Request::param('size') ?? 15;
        $skip = ($page-1)*$size;
        $list = Db::query("select a.anchorid,a.uid,b.intimacy,b.intimacy_week from db_anchor_guardian a LEFT JOIN db_intimacy b on a.anchorid = b.anchorid and a.uid = b.uid where a.anchorid = {$anchorid} and a.expire_time > '{$now}' ORDER BY b.intimacy_week desc, a.expire_time desc , a.id desc limit {$skip},{$size}");
        $uids = array_column($list,'uid');
        $users = UserModel::where('id','in',$uids)->with('profile')->field("id, nick_name, is_anchor, vip_date, vip_level, avatar, user_level, anchor_level")->select()->toArray();
        foreach ($list as &$item){
            foreach ($users as $user){
                if ($item['uid'] == $user['id']){
                    $item['user'] = $user;
                }
            }
        }
        return self::bulidSuccess($list);
    }
}