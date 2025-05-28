<?php


namespace app\webapi\controller;


use app\common\model\AnchorIncomeModel;
use app\common\model\GiftLogModel;
use app\common\model\GuildModel;
use app\common\model\GuildProfitModel;
use app\common\model\IntimacyModel;
use app\common\model\LiveCategoryModel;
use app\common\model\LiveHistoryModel;
use app\common\model\LiveModel;
use app\common\model\LiveRoomBanneduserModel;
use app\common\model\LiveRoomManagerModel;
use app\common\model\UserConsumeModel;
use app\common\model\UserModel;
use app\common\model\UserProfitModel;
use app\common\TXService;
use think\Db;
use think\facade\Request;

class LiveController extends BaseController
{
    /**
     * 需要登录的方法
     */
    protected $NeedLogin = ['getStartLiveParams','startLive','endLive','timeBilling','checkIsMgr','getMgrList','getBannedUserList','banUser','setRoomMgr'];

    /**
     * 参数校验规则
     */
    protected $rules = [
        'startlive'=>[
            'title'=>'require',
            'cateid'=>'require',
            'thumb'=>'require',
            'pull_url'=>'require',
            'stream'=>'require',
        ],
        //计时扣费
        'timebilling'=>[
            'liveid'=>'require',
        ],
    ];

    public function initialize()
    {
        parent::initialize();
    }


    public function getListByCategory(){
        $page = Request::param("page") ?? 1;
        $size = Request::param('size') ?? 25;
        if ($categoryid = Request::param('categoryid')){
            $list = LiveModel::where('categoryid',$categoryid)->with('anchor')->order(['rec_weight'=>'desc','hot'=>'desc','start_stamp'=>'desc'])->limit(($page-1)*$size,$size)->select();
            $count = LiveModel::where('categoryid',$categoryid)->count("*");
        }else{
            $list = LiveModel::order(['rec_weight'=>'desc','hot'=>'desc','start_stamp'=>'desc'])->with('anchor')->limit(($page-1)*$size,$size)->select();
            $count = LiveModel::count("*");
        }
        return self::bulidSuccess(['list'=>$list,'count'=>$count]);
    }

    public function getCategory(){
        //直播分类
        $live_cate = LiveCategoryModel::where(['status'=>1])->order(['sort','id'])->select();
        return self::bulidSuccess($live_cate);
    }

    public function getLivePageData(){
        $anchorid = Request::param('anchorid');
        $liveModel = LiveModel::where(['anchorid'=>$anchorid])->find();
        $anchorModel = UserModel::with('profile')->where(['id'=>$anchorid])->field("id, nick_name, user_level, avatar, is_anchor, vip_date, vip_level, anchor_level")->find();
        if(!$anchorModel){
            return self::bulidFail('主播信息不存在');
        }
        $anchorModel->fans_count = getFansCount($anchorid);
        if ($uid = Request::param('uid')){
            $attentedIds = getUserAttentAnchorIds($uid);
            if (in_array($anchorid,$attentedIds)){
                $anchorModel->isattent = 1;
            }
        }
        $rec_lives = [];
        if (!$liveModel){
            $rec_lives = LiveModel::orderRaw('RAND()')->with('anchor')->limit(0,3)->select();
            return self::bulidSuccess(['live'=>$liveModel,'anchor'=>$anchorModel,'rec_lives'=>$rec_lives]);
        }
        LiveModel::where(['anchorid'=>$anchorid])->update(['hot'=>['inc',10]]);
        return self::bulidSuccess(['live'=>$liveModel,'anchor'=>$anchorModel,'rec_lives'=>$rec_lives]);
    }

    public function getStartLiveParams(){
        if (!$this->userinfo->is_anchor){
            return self::bulidFail('该账号尚未认证主播');
        }
        $uid = $this->userinfo->id;
        $stream = $uid.'_'.time();

        $pull_url = CreateLiveUrl('flv',$stream,0);
        $push = CreateLiveUrl('rtmp',$stream,1);
        $push_url = $push[0];
        $stream_name = $push[1];

        return self::bulidSuccess(['push_url'=>$push_url,'pull_url'=>$pull_url,'stream'=>$stream,'stream_name'=>$stream_name]);
    }

    public function startLive(){
        if (!$this->userinfo->is_anchor){
            return self::bulidFail('该账号尚未认证主播');
        }
        $uid = $this->userinfo->id;
        $title = Request::param('title');
        $cateid = Request::param('cateid');
        $thumb = Request::param('thumb');
        $pull_url = Request::param('pull_url');
        $stream = Request::param('stream');
        $orientation = 1;
        $room_type = Request::param('room_type') ?? 0;
        $pwd = '';
        if (Request::param('pwd')){
            $pwd = md5(Request::param('pwd'));
        }
        $price = Request::param('price') ?? 0;

        $liveid = time().substr(strval($this->userinfo->id),4,4);

        $live = new LiveModel(['anchorid'=>$uid,'liveid'=>$liveid,'title'=>$title,'thumb'=>$thumb,'stream'=>$stream,'categoryid'=>$cateid,'orientation'=>$orientation,'start_stamp'=>time(),'start_time'=>nowFormat(),'pull_url'=>$pull_url,'rec_weight'=>$this->userinfo->rec_weight,'room_type'=>$room_type,'price'=>$price,'password'=>$pwd]);
        if ($live->save()){
            return self::bulidSuccess($live);
        }
        return self::bulidFail();
    }

    public function endLive(){
        Db::startTrans();
        $anchor = $this->userinfo;
        $live = LiveModel::where('anchorid',$anchor->id)->find();
        if (!$live){
            return self::bulidSuccess();
        }
        //计算直播收益
        $liveHistory = new LiveHistoryModel(['anchorid'=>$anchor->id,'liveid'=>$live->liveid,'title'=>$live->title,'stream'=>$live->stream,'pull_url'=>$live->pull_url,'categoryid'=>$live->categoryid,'orientation'=>$live->orientation,'start_stamp'=>$live->start_stamp,'end_stamp'=>time(),'start_time'=>$live->start_time,'end_time'=>nowFormat(),'gift_profit'=>$live->profit]);

        //公会分红
        $guildup = true;
        if ($anchor->guildid){
            $guild = GuildModel::get($anchor->guildid);
            if ($guild){
                $guild_diamond_get = round($live->profit * ($guild->sharing_ratio - $anchor->sharing_ratio) / 100);
                $guild->diamond = ['inc', $guild_diamond_get];
                $guild->diamond_total = ['inc', $guild_diamond_get];

                //写入公会收益记录
                $guild_profit = new GuildProfitModel(['guildid'=>$guild->id, 'diamond'=>$guild_diamond_get, 'content'=>"旗下主播(ID:{$anchor->id})直播(ID:{$live->liveid})收益分红：{$guild_diamond_get}钻石", 'create_time'=>nowFormat()]);
                if(!$guild->save() || !$guild_profit->save()){
                    $guildup = false;
                }
            }
        }

        //写入主播当日收入统计 用于排行榜
        $anchorIncome = AnchorIncomeModel::where(['anchorid'=>$this->userinfo->id])->whereTime('date','today')->find();
        if (!$anchorIncome){
            $anchorIncome = new AnchorIncomeModel(['anchorid'=>$this->userinfo->id,'income'=>0,'date'=>date('Y-m-d')]);
        }
        $anchorIncome->income = ['inc',$live->profit];

        if ($live->delete() && $liveHistory->save() && $anchorIncome->save() && $guildup) {
            Db::commit();
            //清空redis观众列表
            setLiveAudience($anchor->id,[]);
            //通知群内成员
            $endelem = TXService::buildCustomElem('LiveFinished');
            TXService::sendChatRoomMsg(getAnchorRoomID($this->userinfo->id),$endelem);
            return self::bulidSuccess($liveHistory);
        }else{
            Db::rollback();
            return self::bulidFail();
        }
    }

    public function timeBilling(){
        $liveid = Request::param('liveid');
        $live = LiveModel::where(['liveid'=>$liveid])->find();
        if (!$live){
            return self::bulidFail('直播不存在或已结束');
        }
        if ($live->room_type != 2){
            return self::bulidFail('该房间不是付费房间');
        }
        if ($this->userinfo->gold < $live->price){
            return self::bulidChargeFail();
        }
        $user = $this->userinfo;
        $usergoldleft = $user->gold - $live->price;

        $anchor = UserModel::where(['id'=>$live->anchorid])->field(['id,nick_name','online_status','sharing_ratio','guildid'])->find();

        //扣除用户金币
        $user->gold = ['dec', $live->price];
        //增加主播钻石
        $anchor_diamond_get = round($live->price * $anchor->sharing_ratio / 100);
        $anchor->diamond = ['inc', $anchor_diamond_get];
        $anchor->diamond_total = ['inc', $anchor_diamond_get];
        //增加主播经验值
        $anchor->anchor_point = ['inc',$live->price];

        //写入主播收益记录
        $anchor_profit = new UserProfitModel(['uid'=>$anchor->id, 'coin_count'=>$anchor_diamond_get, 'content'=>"用户{$user->nick_name}(ID:{$user->id})观看直播，收入：{$anchor_diamond_get}钻石", 'type'=>1, 'consume_type'=>0, 'resid'=>0,'create_time'=>nowFormat()]);
        //写入用户消费记录
        $user_profit = new UserProfitModel(['uid'=>$user->id, 'coin_count'=>$live->price, 'content'=>"观看付费直播(ID:{$live->liveid})消耗：{$live->price}金币", 'type'=>0, 'consume_type'=>0, 'resid'=>0, 'create_time'=>nowFormat()]);

        //增加亲密度
        $intimacy = IntimacyModel::where(['anchorid'=>$anchor->id,'uid'=>$user->id])->find();
        if ($intimacy){
            $intimacy->intimacy = ['inc', $live->price];
            $intimacy->intimacy_week = ['inc', $live->price];
        }else{
            $intimacy = new IntimacyModel();
            $intimacy->uid = $user->id;
            $intimacy->anchorid = $anchor->id;
            $intimacy->intimacy = $live->price;
            $intimacy->intimacy_week = $live->price;
        }

        //写入用户当日消费统计 用于排行榜
        $userConsume = UserConsumeModel::where(['uid'=>$user->id])->whereTime('date','today')->find();
        if (!$userConsume){
            $userConsume = new UserConsumeModel(['uid'=>$user->id,'consume'=>0,'date'=>date('Y-m-d')]);
        }
        $userConsume->consume = ['inc',$live->price];

        //写入直播收益
        $live_profit_update = Db::table('db_live')->where('liveid',$liveid)->inc('profit',$live->price)->update();

        Db::startTrans();
        if($anchor->save() && $anchor_profit->save() && $user->save() && $user_profit->save() && $intimacy->save() && $userConsume->save() && $live_profit_update){
            Db::commit();
            self::echoSuccess(['gold'=>$usergoldleft]);
            //增加直播热度
            if ($liveid){
                LiveModel::update(['hot'=>['inc',$live->price*100]],['liveid'=>$liveid]);
            }
            exit();
        }else{
            Db::rollback();
            return self::bulidCodeFail(1001,"赠送失败");
        }
    }

    public function checkIsMgr(){
        $anchorid = Request::param('anchorid');
        $mgr = LiveRoomManagerModel::where(['anchorid'=>$anchorid,'mgrid'=>$this->userinfo->id])->find();
        if ($mgr){
            return self::bulidSuccess();
        }
        return self::bulidFail();
    }

    public function getMgrList(){
        $list = LiveRoomManagerModel::where(['anchorid'=>$this->userinfo->id])->with('user')->select();
        return self::bulidSuccess($list);
    }

    public function getBannedUserList(){
        $page = Request::param('page') ?? 1;
        $size = Request::param('size') ?? 20;
        $anchorid = Request::param('anchorid');
        if ($anchorid != $this->userinfo->id){
            $mgr = LiveRoomManagerModel::where(['anchorid'=>$anchorid,'mgrid'=>$this->userinfo->id])->find();
            if (!$mgr){
                return self::bulidFail('权限不足');
            }
        }
        $list = LiveRoomBanneduserModel::where(['anchorid'=>$anchorid])->with('user')->order(['create_time'=>'desc'])->limit(($page-1)*$size,$size)->select();
        $users = [];
        foreach ($list as $ban){
            $users[] = $ban->user;
        }
        $count = LiveRoomBanneduserModel::where(['anchorid'=>$anchorid])->count('id');
        return self::bulidSuccess(['list'=>$users,'count'=>$count]);
    }

    public function banUser(){
        $anchorid = Request::param('anchorid');
        $userid = Request::param('userid');
        $type = Request::param('type');
        $bannedUser = LiveRoomBanneduserModel::where(['anchorid'=>$anchorid,'uid'=>$userid])->find();
        if ($type != 1){
            //解禁
            if (!$bannedUser){
                return self::bulidSuccess();
            }
        }else{
            //禁言
            if ($bannedUser){
                return self::bulidSuccess();
            }
            //检测是否vip4
            $userVip = UserModel::where(['id'=>$userid,'vip_level'=>4])->whereTime('vip_date','>',nowFormat())->find();
            if ($userVip){
                return self::bulidFail('4级贵族用户无法被禁言');
            }
        }
        if ($anchorid != $this->userinfo->id){
            $mgr = LiveRoomManagerModel::where(['anchorid'=>$anchorid,'mgrid'=>$this->userinfo->id])->find();
            if (!$mgr){
                return self::bulidFail('权限不足');
            }
        }
        $time = $type == 1?9999999999:0;
        $banRes = TXService::banUser(getAnchorRoomID($anchorid),[strval($userid)],$time);
        if ($banRes['ActionStatus'] == 'OK'){
            if ($type == 1){
                $bannedUser = new LiveRoomBanneduserModel(['uid'=>$userid,'anchorid'=>$anchorid,'mgrid'=>$this->userinfo->id,'create_time'=>nowFormat()]);
                if ($bannedUser->save()){
                    return self::bulidSuccess();
                }else{
                    TXService::banUser(getAnchorRoomID($anchorid),[strval($userid)],0);
                    return self::bulidFail();
                }
            }else{
                if ($bannedUser->delete()){
                    return self::bulidSuccess();
                }else{
                    TXService::banUser(getAnchorRoomID($anchorid),[strval($userid)],9999999999);
                    return self::bulidFail();
                }
            }
        }
        return self::bulidFail();
    }

    public function setRoomMgr(){
        $mgrid = Request::param('mgrid');
        $type = Request::param('type');
        $mgr = LiveRoomManagerModel::where(['anchorid'=>$this->userinfo->id,'mgrid'=>$mgrid])->with('user')->find();
        if ($type == 0){
            if (!$mgr){
                $user = UserModel::where(['id'=>$mgrid])->field("id, nick_name, user_level, avatar, is_anchor, vip_date, vip_level, anchor_level")->find();
                self::sendMgrNotify($this->userinfo->id,$user,0);
                return self::bulidSuccess();
            }elseif ($mgr->delete()){
                self::sendMgrNotify($this->userinfo->id,$mgr->user,0);
                return self::bulidSuccess();
            }
            return self::bulidFail();
        }else {
            if ($mgr){
                self::sendMgrNotify($this->userinfo->id,$mgr->user,1);
                return self::bulidSuccess();
            }
            $mgrCount = LiveRoomManagerModel::where(['anchorid' => $this->userinfo->id])->count('id');
            if ($mgrCount >= 5){
                return self::bulidFail('管理员数量已达上限');
            }
            $mgr = new LiveRoomManagerModel(['anchorid'=>$this->userinfo->id,'mgrid'=>$mgrid,'create_time'=>nowFormat()]);
            if ($mgr->save()){
                $user = UserModel::where(['id'=>$mgrid])->field("id, nick_name, user_level, avatar, is_anchor, vip_date, vip_level, anchor_level")->find();
                self::sendMgrNotify($this->userinfo->id,$user,1);
                return self::bulidSuccess();
            }
            return self::bulidFail();
        }
    }

    private static function sendMgrNotify($anchorid,$mgr,$isSet = 0){
        $type = $isSet?"RoomNotifyTypeSetManager":"RoomNotifyTypeCancelManager";
        $im_ext['notify'] = ['type'=>$type,'user'=>$mgr];
        $elem = TXService::buildCustomElem('RoomNotification',$im_ext);
        TXService::sendChatRoomMsg(getAnchorRoomID($anchorid),$elem);
    }

}