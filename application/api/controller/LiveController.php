<?php


namespace app\api\controller;


use app\common\model\AnchorFansModel;
use app\common\model\AnchorIncomeModel;
use app\common\model\GiftLogModel;
use app\common\model\GuildModel;
use app\common\model\GuildProfitModel;
use app\common\model\IntimacyModel;
use app\common\model\LiveHistoryModel;
use app\common\model\LiveModel;
use app\common\model\LivePkModel;
use app\common\model\LiveRoomBanneduserModel;
use app\common\model\LiveRoomManagerModel;
use app\common\model\ShopGoodsModel;
use app\common\model\UserConsumeModel;
use app\common\model\UserModel;
use app\common\model\UserProfitModel;
use app\common\NSLog;
use app\common\QcloudSTS;
use app\common\TXService;
use TencentCloud\Common\Credential;
use TencentCloud\Common\Exception\TencentCloudSDKException;
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Common\Profile\HttpProfile;
use TencentCloud\Live\V20180801\LiveClient;
use TencentCloud\Live\V20180801\Models\CancelCommonMixStreamRequest;
use TencentCloud\Live\V20180801\Models\CreateCommonMixStreamRequest;
use think\Db;
use think\facade\Request;

class LiveController extends BaseController
{
    protected $NeedLogin = ['startLive','mlvbStartLive','endLive','timeBilling','explainingGoods','checkIsMgr','getMgrList','getBannedUserList','banUser','setRoomMgr','setLinkOnOff','requestMlvbLink','acceptMlvbLink','refuseMlvbLink','stopMlvbLink','mergeStream','enterPkMode','endPk','getGroupUserData'];

    protected $rules = array(

        //开启直播
        'startlive'=>array(
            'cateid'=>'require',
            'thumb'=>'require',
            'title'=>'require',
            'orientation'=>'require'
        ),
        //开启直播
        'mlvbstartlive'=>array(
            'cateid'=>'require',
            'thumb'=>'require',
            'title'=>'require',
            'orientation'=>'require',
            //'mlvb_token'=>'require',
        ),

        //申请连麦
        'requestmlvblink'=>[
            'anchorid'=>'require',
            //'mlvb_token'=>'require',
        ],

        //主播接受连麦
        'acceptmlvblink'=>[
            'userid'=>'require',
        ],

        //主播拒绝连麦
        'refusemlvblink'=>[
            'userid'=>'require',
        ],

        //结束连麦
        'stopmlvblink'=>[
            'anchorid'=>'require',
            'linkerid'=>'require',
        ],

        //结束直播
        'endlive'=>array(
            'liveid'=>'require',
        ),

        //直播信息
        'getliveinfo'=>array(
            'liveid'=>'require',
        ),

        //直播详细信息
        'getanchorliveinfo'=>array(
            'anchorid'=>'require',
        ),

        //直播贡献排行
        'getcontributerank'=>array(
            'liveid'=>'require',
        ),

        //计时扣费
        'timebilling'=>[
            'liveid'=>'require',
        ],

        'getgoodslist'=>[
            'anchorid'=>'require',
        ],

        //开始讲解商品
        'explaininggoods'=>[
            'goodsid'=>'require',
            'type'=>'require', //0-结束 1-开始
        ],
        //搜索
        'search'=>[
            'keyword'=>'require'
        ],
        //检测是否是管理员
        'checkismgr'=>[
            'anchorid'=>'require',
        ],
        //禁言用户
        'banuser'=>[
            'anchorid'=>'require',
            'userid'=>'require',
            'type'=>'require' //0-解禁 1-禁言
        ],
        //设置房管
        'setroommgr'=>[
            'mgrid'=>'require',
            'type'=>'require',  //0-取消 1-设置
        ],
        //禁言用户列表
        'getBannedUserList'=>[
            'anchorid'=>'require'
        ],
        //主播开启关闭连麦
        'selLinkonoff'=>[
            'type'=>'require' //0-关闭 1-开启
        ]
    );

    public function initialize()
    {
        parent::initialize();
    }

    public function getHotLives(){
        $page = Request::post("page");
        $size = Request::post("size");
        if (!$page){
            $page = 1;
        }
        if (!$size){
            $size = 10;
        }
        $lives = LiveModel::with(['anchor'])->order(['rec_weight'=>'desc','hot'=>'desc','start_stamp'=>'desc'])->limit(($page-1)*$size,$size)->select();


        if ($uid = Request::post("uid")){
            $attentids = getUserAttentAnchorIds($uid);
            foreach ($attentids as $attentid){
                foreach ($lives as $index=>$live){
                    if ($live->anchor->id == $attentid){
                        $live->anchor->isattent = 1;
                    }
                }
            }
        }

        return self::bulidSuccess($lives);
    }

    public function getLivesByCategory(){
        $page = Request::post("page");
        $size = Request::post("size");
        $categoryid = Request::post('categoryid');

        if (!$page){
            $page = 1;
        }
        if (!$size){
            $size = 10;
        }
        $lives = LiveModel::with(['anchor'])->where(['categoryid'=>$categoryid])->order(['start_stamp'=>'desc'])->limit(($page-1)*$size,$size)->select();
        if ($uid = Request::post("uid")){
            $attentids = getUserAttentAnchorIds($uid);
            foreach ($attentids as $attentid){
                foreach ($lives as $index=>$live){
                    if ($live->anchor->id == $attentid){
                        $live->anchor->isattent = 1;
                    }
                }
            }
        }

        return self::bulidSuccess($lives);
    }

    public function search(){
        $page = Request::post("page") ?? 1;
        $size = Request::post("size") ?? 20;
        $keyword = Request::post("keyword");
        $lives = LiveModel::whereRaw("title like '%{$keyword}%'")->with(['anchor'])->order(['rec_weight'=>'desc','hot'=>'desc','start_stamp'=>'desc'])->limit($size*($page-1),$size)->select();

        if ($uid = Request::post("uid")){
            $attentids = getUserAttentAnchorIds($uid);
            foreach ($attentids as $attentid){
                foreach ($lives as $index=>$live){
                    if ($live->anchor->id == $attentid){
                        $live->anchor->isattent = 1;
                    }
                }
            }
        }

        return self::bulidSuccess($lives);
    }

    public function searchLive(){
        $keyword = Request::post("keyword");
        $lives = LiveModel::whereRaw("title like '%{$keyword}%'")->with(['anchor'])->order(['rec_weight'=>'desc','hot'=>'desc','start_stamp'=>'desc'])->select();

        if ($uid = Request::post("uid")){
            $attentids = getUserAttentAnchorIds($uid);
            foreach ($attentids as $attentid){
                foreach ($lives as $index=>$live){
                    if ($live->anchor->id == $attentid){
                        $live->anchor->isattent = 1;
                    }
                }
            }
        }

        return self::bulidSuccess($lives);
    }

    public function startLive(){
        if (!$this->userinfo->is_anchor){
            return self::bulidFail('该账号尚未认证主播');
        }
        $uid = $this->userinfo->id;
        $title = Request::param('title');
        $cateid = Request::param('cateid');
        $thumb = Request::param('thumb');
        $orientation = Request::param('orientation');
        $room_type = Request::param('room_type') ?? 0;
        $pwd = '';
        if (Request::param('pwd')){
            $pwd = md5(Request::param('pwd'));
        }
        $price = Request::param('price') ?? 0;

        $liveid = time().substr(strval($this->userinfo->id),4,4);
        $stream = $uid.'_'.time();

        $pull_url = CreateLiveUrl('flv',$stream,0);
        $push_url = CreateLiveUrl('rtmp',$stream,1)[0];
        $live = new LiveModel(['anchorid'=>$uid,'liveid'=>$liveid,'title'=>$title,'thumb'=>$thumb,'stream'=>$stream,'categoryid'=>$cateid,'orientation'=>$orientation,'start_stamp'=>time(),'start_time'=>nowFormat(),'pull_url'=>$pull_url,'rec_weight'=>$this->userinfo->rec_weight,'room_type'=>$room_type,'price'=>$price,'password'=>$pwd]);
        if ($live->save()){
            $live->push_url = $push_url;
            return self::bulidSuccess($live);
        }
        return self::bulidFail();
    }

    public function mlvbStartLive(){
        if (!$this->userinfo->is_anchor){
            return self::bulidFail('该账号尚未认证主播');
        }
        $uid = $this->userinfo->id;
        //$mlvb_token = Request::param('mlvb_token');
        $title = Request::param('title');
        $cateid = Request::param('cateid');
        $thumb = Request::param('thumb');
        $orientation = Request::param('orientation');
        $room_type = Request::param('room_type') ?? 0;
        $pwd = '';
        if (Request::param('pwd')){
            $pwd = md5(Request::param('pwd'));
        }
        $price = Request::param('price') ?? 0;
        $liveid = time().substr(strval($this->userinfo->id),4,4);
        $push_url = TrtcPush($uid); // 推流
        $pull_url = TrtcPlayLiveUrl($uid, 'flv')['url']; // 观众拉流
        $live     = LiveModel::where(["anchorid" => $uid])->find();
        $stream = 'st_'.$uid;
        if ($live) {
            $update_data = [
                'liveid' => $liveid, 
                'title' => $title, 
                'thumb' => $thumb, 
                'categoryid' => $cateid, 
                'orientation' => $orientation, 
                'status' => 1,
                'stream' => $stream,
                'push_url' => $push_url,
                'pull_url' => $pull_url,
                'acc_pull_url' => '',//TrtcPlay($uid),
                'rec_weight' => $this->userinfo->rec_weight,
                'room_type' => $room_type, 
                'price' => $price, 
                'password' => $pwd,
                'link_on' => 0,
            ];
            if(!$live->save($update_data)){
                return self::bulidFail();
            }
        } else {
            $live = new LiveModel();
            $save_data = [
                'anchorid' => $uid, 
                'liveid' => $liveid, 
                'title' => $title, 
                'thumb' => $thumb, 
                'stream' => $stream, 
                'categoryid' => $cateid, 
                'orientation' => $orientation, 
                'start_stamp' => time(), 
                'start_time' => nowFormat(),
                'push_url' => $push_url, 
                'pull_url' => $pull_url, 
                'acc_pull_url' => '',//TrtcPlay($uid), 
                'rec_weight' => $this->userinfo->rec_weight,
                'room_type' => $room_type, 
                'price' => $price, 
                'password' => $pwd
            ];
            if (!$live->save($save_data)) {
                return self::bulidFail();
            }
        }
        $live->push_url = $push_url;
        return self::bulidSuccess($live);
    }

    // 观众申请连麦
    public function requestMlvbLink(){
        $anchorid = Request::param('anchorid');
        $live = LiveModel::where(['anchorid'=>$anchorid])->find();
        if (!$live){
            return self::bulidFail('主播未开播');
        }
        if (!$live->link_on){
            return self::bulidFail('主播暂未开启连麦功能');
        }
        if ($live->link_status){
            return self::bulidFail('主播正在连麦中');
        }
        //检测是否vip
        if ($this->userinfo->vip_level > 0 && strtotime($this->userinfo->vip_date) > time()){
            //$mlvb_token = Request::param('mlvb_token');
            //$t_result = self::createMlvbPushUrl($this->userinfo->id,$mlvb_token);
            //通知主播
            $user_play_stream = 'st_'.$this->userinfo->id;
            $im_ext['notify'] = [
                'type'=>'RoomNotificationReciveLinkRequest',
                'user'=>$this->userinfo->visible(['id','avatar','nick_name','vip_level','vip_date']),
                'link_acc_url'=> TrtcPlay($anchorid, $user_play_stream), // 主播拉取观众的流//$t_result['accelerateURL'],
                'touid'=>$anchorid
            ];
            $elem = TXService::buildCustomElem('RoomNotification',$im_ext);
            $tx_res = TXService::sendChatRoomMsg(getAnchorRoomID($anchorid),$elem);
            if ($tx_res['ActionStatus'] == 'OK'){
                $push_url = TrtcPush($this->userinfo->id); // 申请者推流地址
                return self::bulidSuccess(['push_url'=>$push_url]);
            }else{
                return self::bulidFail();
            }
        }else{
            return self::bulidFail('VIP贵族才可以申请连麦');
        }
    }

    static private function createMlvbPushUrl($uid,$token){
        //生成推流地址
        $t_url = "https://liveroom.qcloud.com/weapp/live_room/get_anchor_url?userID={$uid}&token={$token}";
        $t_resp = httpHelper($t_url,json_encode(['userID'=>$uid]));
        return json_decode($t_resp,true);
    }

    // 主播同意
    public function acceptMlvbLink(){
        $userId = Request::param('userid'); // 连麦观众
        $live = LiveModel::where(['anchorid'=>$this->userinfo->id])->find();
        if (!$live){
            return self::bulidFail('未开播');
        }
        if ($live->link_status == 1){
            return self::bulidFail('已经在连麦中');
        }
        if (LiveModel::where(['anchorid'=>$this->userinfo->id])->update(['link_status'=>1])) {
            $anchor_play_stream = 'st_'.$this->userinfo->id; // 主播流
            //通知连麦用户
            $im_ext['notify'] = [
                'type'=>'RoomNotificationAcceptLinkRequest',
                'user'=>$this->userinfo->visible(['id']),
                'touid'=>$userId,
                'link_acc_url'=>TrtcPlay($userId, $anchor_play_stream) // 观众拉取主播的流
            ];
            $elem = TXService::buildCustomElem('RoomNotification',$im_ext);
            $tx_res = TXService::sendChatRoomMsg(getAnchorRoomID($this->userinfo->id),$elem);
            if ($tx_res['ActionStatus'] == 'OK') {
                $user_play_stream = 'st_'.$userId; // 观众流
                $play_url = TrtcPlay($this->userinfo->id, $user_play_stream);// 主播拉取观众的流
                return self::bulidSuccess(['play_url'=>$play_url]);
            }
        }
        return self::bulidFail();
    }

    public function refuseMlvbLink(){
        //通知连麦用户
        $im_ext['notify'] = ['type'=>'RoomNotificationRefuseLinkRequest','user'=>$this->userinfo->visible(['id']),'touid'=>Request::param('userid')];
        $elem = TXService::buildCustomElem('RoomNotification',$im_ext);
        $tx_res = TXService::sendChatRoomMsg(getAnchorRoomID($this->userinfo->id),$elem);
        if ($tx_res['ActionStatus'] == 'OK') {
            return self::bulidSuccess();
        }
        return self::bulidFail();
    }

    public function stopMlvbLink(){
        $anchorid = Request::param('anchorid');
        Db::startTrans();
        if (LiveModel::where(['anchorid'=>$anchorid])->update(['link_status'=>0])){
            if ($anchorid == $this->userinfo->id){
                //结束混流
                /*if (!self::mergeStreamStop($anchorid)){
                    Db::rollback();
                    return self::bulidFail();
                }*/
                //通知连麦用户
                $im_ext['notify'] = ['type'=>'RoomNotificationStopLink','user'=>$this->userinfo->visible(['id']),'touid'=>Request::param('linkerid')];
                $elem = TXService::buildCustomElem('RoomNotification',$im_ext);
                $tx_res = TXService::sendChatRoomMsg(getAnchorRoomID($anchorid),$elem);
                if ($tx_res['ActionStatus'] == 'OK') {
                    Db::commit();
                    return self::bulidSuccess();
                }
            }else{
                //结束混流
                /*if (!self::mergeStreamStop($anchorid)){
                    Db::rollback();
                    return self::bulidFail();
                }*/
                //通知主播
                $im_ext['notify'] = ['type'=>'RoomNotificationStopLink','user'=>$this->userinfo->visible(['id']),'touid'=>$anchorid];
                $elem = TXService::buildCustomElem('RoomNotification',$im_ext);
                $tx_res = TXService::sendChatRoomMsg(getAnchorRoomID($anchorid),$elem);
                if ($tx_res['ActionStatus'] == 'OK') {
                    Db::commit();
                    return self::bulidSuccess();
                }
            }
        }
        Db::rollback();
        return self::bulidFail();
    }

    public function mergeStream(){
        $config_pri = getConfigPri();
        $linkerid = Request::param('linkerid');
        $anchor_streamid = $config_pri->im_sdkappid .'_'. $this->userinfo->id;
        $linker_streamid = $config_pri->im_sdkappid .'_'. $linkerid;

        try {
            $cred = new Credential($config_pri->qcloud_secretid, $config_pri->qcloud_secretkey);
            $httpProfile = new HttpProfile();
            $httpProfile->setEndpoint("live.tencentcloudapi.com");

            $clientProfile = new ClientProfile();
            $clientProfile->setHttpProfile($httpProfile);
            $client = new LiveClient($cred, "", $clientProfile);

            $req = new CreateCommonMixStreamRequest();

            $params = array(
                "InputStreamList" => array(
                    array(
                        "LayoutParams" => array(
                            "ImageLayer" => 1,
                            "ImageWidth" => 360,
                            "ImageHeight" => 640
                        ),
                        "InputStreamName" => $anchor_streamid
                    ),
                    array(
                        "LayoutParams" => array(
                            "ImageLayer" => 2,
                            "ImageWidth" => 120,
                            "ImageHeight" => 180,
                            "LocationX" => 210,
                            "LocationY"=>350
                        ),
                        "InputStreamName" => $linker_streamid
                    )
                ),
                "OutputParams" => array(
                    "OutputStreamName" => $anchor_streamid
                ),
                "MixStreamSessionId" => strval($this->userinfo->id),
                "MixStreamTemplateId" => 20
            );
            $req->fromJsonString(json_encode($params));

            $resp = $client->CreateCommonMixStream($req);

            if (array_key_exists('Error',$resp) && $resp['Error']){
                return self::bulidFail();
            }
            return self::bulidSuccess();
        }
        catch(TencentCloudSDKException $e) {
            NSLog::writeRuntimeLog('CreateCommonMixStreamRequest');
            return self::bulidFail();
        }
    }

    static private function mergeStreamStop($anchorid){
        $config_pri = getConfigPri();
        try {
            $cred = new Credential($config_pri->qcloud_secretid, $config_pri->qcloud_secretkey);
            $httpProfile = new HttpProfile();
            $httpProfile->setEndpoint("live.tencentcloudapi.com");

            $clientProfile = new ClientProfile();
            $clientProfile->setHttpProfile($httpProfile);
            $client = new LiveClient($cred, "", $clientProfile);

            $req = new CancelCommonMixStreamRequest();

            $params = array(
                "MixStreamSessionId" => strval($anchorid)
            );
            $req->fromJsonString(json_encode($params));

            $resp = $client->CancelCommonMixStream($req);
            if (array_key_exists('Error',$resp) && $resp['Error']){
                return false;
            }
            return true;
        }
        catch(TencentCloudSDKException $e) {
            return false;
        }
    }

    // 开启pk模式 随机匹配
    public function enterPkMode(){
        $pklive = LiveModel::where(['pk_status'=>2])->where('anchorid','<>',$this->userinfo->id)->orderRand()->find();
        if (!$pklive){
            //没有等待pk的直播间
            if (LiveModel::where(['anchorid'=>$this->userinfo->id])->update(['pk_status'=>2])){
                return self::bulidSuccess();
            }
        }else{
            //发起pk
            Db::startTrans();
            $pkModel = new LivePkModel(['home_anchorid'=>$this->userinfo->id,'away_anchorid'=>$pklive->anchorid,'create_time'=>nowFormat()]);
            if ($pkModel->save()){
                $pkdata = ['pk_status'=>1,'pkid'=>$pkModel->id];
                if (LiveModel::where(['liveid'=>$pklive->liveid,'pk_status'=>2])->update($pkdata) && LiveModel::where(['anchorid'=>$this->userinfo->id])->update($pkdata)){
                    //开始pk
                    Db::commit();
                    $pkanchor = UserModel::where(['id'=>$pklive->anchorid])->with('profile')->field("id, nick_name, user_level, avatar, is_anchor, vip_date, vip_level, anchor_level")->find();

                    $home_pk_live = LiveModel::where(['anchorid' => $pkModel['home_anchorid']])->find();
                    $away_pk_live = LiveModel::where(['anchorid' => $pkModel['away_anchorid']])->find();
                    $pkModel['update_time'] = date('Y-m-d H:i:s', time());
                    // 推给A
                    $home_pk_live['acc_pull_url'] = TrtcPlay($pkModel['away_anchorid'], $home_pk_live['stream']);
                    $im_ext_away['notify'] = [
                        'type'=>'RoomNotifyTypePkStart',
                        'pklive'=>$home_pk_live,
                        'pkinfo'=>$pkModel
                    ];
                    $elem_away = TXService::buildCustomElem('RoomNotification',$im_ext_away);
                    TXService::sendChatRoomMsg(getAnchorRoomID($away_pk_live['anchorid']),$elem_away);
                    // 推广B
                    $away_pk_live['acc_pull_url'] = TrtcPlay($pkModel['home_anchorid'], $away_pk_live['stream']);
                    $im_ext_home['notify'] = [
                        'type'=>'RoomNotifyTypePkStart',
                        'pklive'=>$away_pk_live,
                        'pkinfo'=>$pkModel
                    ];
                    $elem_home = TXService::buildCustomElem('RoomNotification',$im_ext_home);
                    TXService::sendChatRoomMsg(getAnchorRoomID($home_pk_live['anchorid']),$elem_home);
                    return self::bulidSuccess(['pk_live'=>$pklive,'pk_anchor'=>$pkanchor,'pk_info'=>$pkModel]);
                }else{
                    Db::rollback();
                    //进入匹配队列
                    if (LiveModel::where(['anchorid'=>$this->userinfo->id])->update(['pk_status'=>2])){
                        return self::bulidSuccess();
                    }
                }
            }
            Db::rollback();
        }
        return self::bulidFail();
    }

    // 结束pk
    public function endPk(){
        $live = LiveModel::where(['anchorid'=>$this->userinfo->id])->find();
        if (!$live){
            return self::bulidFail();
        }
        if ($live->pk_status == 0){
            return self::bulidSuccess();
        }
        if ($live->pkid == 0 && $live->save(['pk_status'=>0])){
            return self::bulidSuccess();
        }
        $pkModel = LivePkModel::where(['id'=>$live->pkid])->find();
        if (!$pkModel && $live->save(['pk_status'=>0,'pkid'=>0])){
            return self::bulidSuccess();
        }
        $pkanchorid = $pkModel->home_anchorid == $this->userinfo->id ? $pkModel->away_anchorid:$pkModel->home_anchorid;
        $pklive = LiveModel::where(['anchorid'=>$pkanchorid,'pkid'=>$pkModel->id])->find();

        $im_ext['notify'] = ['type'=>'RoomNotifyTypePkEnd'];
        $elem = TXService::buildCustomElem('RoomNotification',$im_ext);

        Db::startTrans();
        if (!$pklive || ($pklive->pk_status == 0 && $pklive->pkid == 0)){
            //只需更新自己的直播状态
            if ($live->save(['pk_status'=>0,'pkid'=>0])){
                Db::commit();
                self::echoSuccess();
                //IM通知
                TXService::sendChatRoomMsg(getAnchorRoomID($this->userinfo->id),$elem);
                exit();
            }
        }else{
            if ($live->save(['pk_status'=>0,'pkid'=>0]) && $pklive->save(['pk_status'=>0,'pkid'=>0])){
                Db::commit();
                self::echoSuccess();
                //IM通知
                TXService::sendChatRoomMsg(getAnchorRoomID($this->userinfo->id),$elem);
                TXService::sendChatRoomMsg(getAnchorRoomID($pkanchorid),$elem);
                exit();
            }
        }
        Db::rollback();
        return self::bulidFail();
    }

    // 结束直播
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
            //self::echoSuccess($liveHistory);

            //通知群内成员
            $endelem = TXService::buildCustomElem('LiveFinished');
            TXService::sendChatRoomMsg(getAnchorRoomID($this->userinfo->id),$endelem);

            //结束pk
            if ($live->pkid){
                $pkinfo = LivePkModel::where(['id'=>$live->pkid])->find();
                $pkanchorid = $pkinfo->home_anchorid == $liveHistory->anchorid ? $pkinfo->away_anchorid : $pkinfo->home_anchorid;
                if (LiveModel::where(['anchorid'=>$pkanchorid])->update(['pkid'=>0,'pk_status'=>0])){
                    $im_ext['notify'] = ['type'=>'RoomNotifyTypePkEnd'];
                    $elem = TXService::buildCustomElem('RoomNotification',$im_ext);
                    TXService::sendChatRoomMsg(getAnchorRoomID($pkanchorid),$elem);
                }
            }
            //exit();
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

    // v3.1弃用
    public function getLiveInfo(){
        $liveid = Request::param('liveid');

        //增加热度
        LiveModel::update(['hot'=>['inc',10]],['liveid'=>$liveid]);

        $live = LiveModel::where(['liveid'=>$liveid])->with('anchor')->find();
        if (!$live){
            return self::bulidFail('主播休息中');
        }
        //增加主播经验
        UserModel::update(['anchor_point'=>['inc',10]],['id'=>$live->anchorid]);

        //查询贡献榜前十
        $contribute = GiftLogModel::where('liveid', $liveid)->field('id,uid,anchorid,liveid,sum(spend) as intimacy')->with('user')->group('uid')->order('intimacy desc')->limit(0,10)->select();
        if (count($contribute) < 5){
            $ids = [];
            foreach ($contribute as $item){
                $ids[] = $item->user->id;
            }
            $audienceids = getLiveAudience($live->anchorid);
            if ($audienceids >= 10){
                $newConids = array_slice($audienceids,0,10);
            }else{
                $newConids = $audienceids;
            }
            $users = UserModel::where('id','in',$newConids)->where('id','not in',$ids)->with('profile')->field("id, nick_name, user_level, avatar, is_anchor, vip_date, vip_level, anchor_level")->select();
            foreach ($users as $user){
                $giftlogModel = new GiftLogModel(['uid'=>$user->id,'anchorid'=>$live->anchorid]);
                $giftlogModel->user = $user;
                $contribute[] = $giftlogModel;
            }
        }

        //查询本场收益
        $profit = GiftLogModel::where(['liveid'=>$liveid])->sum('spend');
        $live->gift_profit = $profit;

        //查询主播粉丝数量
        $fansCount = getFansCount($live->anchorid);
        $live->anchor->fans_count = $fansCount;

        //查询是否已关注主播
        if ($uid = Request::param('uid')){
            $isattent = AnchorFansModel::where(['anchorid'=>$live->anchorid,'fansid'=>$uid])->find();
            if ($isattent){
                $live->anchor->isattent = 1;
            }
        }

        return self::bulidSuccess(['live'=>$live,'contribute'=>$contribute]);
    }

    // v3.1
    public function getAnchorLiveInfo(){
        $anchorid = Request::param('anchorid');

        //增加热度
        LiveModel::update(['hot'=>['inc',10]],['anchorid'=>$anchorid]);

        $live = LiveModel::where(['anchorid'=>$anchorid])->find();
        if (!$live){
            return self::bulidFail('主播休息中');
        }
        //增加主播经验
        UserModel::update(['anchor_point'=>['inc',10]],['id'=>$live->anchorid]);

        if ($live->pk_status == 1){
            //查询pk信息
            $pkinfo = LivePkModel::where(['id'=>$live->pkid])->find();
            $pkinfo['update_time'] = date('Y-m-d H:i:s', time());
            $pkanchorid = $pkinfo->home_anchorid == $anchorid?$pkinfo->away_anchorid:$pkinfo->home_anchorid;
            $pklive = LiveModel::where(['anchorid'=>$pkanchorid])->find();
            $live->pkinfo = $pkinfo;
            $live->pklive = $pklive;
        }

        //查询贡献榜前十
        $contribute = GiftLogModel::where('liveid', $live->liveid)->field('id,uid,anchorid,liveid,sum(spend) as intimacy')->with('user')->group('uid')->order('intimacy desc')->limit(0,10)->select();
        if (count($contribute) < 5){
            $ids = [];
            foreach ($contribute as $item){
                $ids[] = $item->user->id;
            }
            $audienceids = getLiveAudience($live->anchorid);
            if ($audienceids >= 10){
                $newConids = array_slice($audienceids,0,10);
            }else{
                $newConids = $audienceids;
            }
            $users = UserModel::where('id','in',$newConids)->where('id','not in',$ids)->with('profile')->field("id, nick_name, user_level, avatar, is_anchor, vip_date, vip_level, anchor_level")->select();
            foreach ($users as $user){
                $giftlogModel = new GiftLogModel(['uid'=>$user->id,'anchorid'=>$live->anchorid]);
                $giftlogModel->user = $user;
                $contribute[] = $giftlogModel;
            }
        }
        return self::bulidSuccess(['live'=>$live,'contribute'=>$contribute]);
    }

    // V-3.1
    public function getLiveBasicInfo(){
        $liveid = Request::param('liveid');

        //增加热度
        LiveModel::update(['hot'=>['inc',10]],['liveid'=>$liveid]);

        $live = LiveModel::where(['liveid'=>$liveid])->field(['anchorid,profit,hot'])->find();
        if (!$live){
            return self::bulidFail('主播休息中');
        }
        //增加主播经验
        UserModel::update(['anchor_point'=>['inc',10]],['id'=>$live->anchorid]);

        //查询贡献榜前十
        $contribute = GiftLogModel::where('liveid', $liveid)->field('id,uid,anchorid,liveid,sum(spend) as intimacy')->with('user')->group('uid')->order('intimacy desc')->limit(0,10)->select();

        //查询主播粉丝数量
        $fansCount = getFansCount($live->anchorid);

        //查询是否已关注主播
        $isattent = 0;
        if ($uid = Request::param('uid')){
            $attent = AnchorFansModel::where(['anchorid'=>$live->anchorid,'fansid'=>$uid])->find();
            if ($attent){
                $isattent = 1;
            }
        }

        return self::bulidSuccess(['live'=>$live, 'fans_count'=>$fansCount, 'isattent'=>$isattent, 'contribute'=>$contribute]);
    }

    public function getContributeRank(){
        $liveid = Request::param('liveid');

        $contribute = GiftLogModel::where('liveid', $liveid)->field('id,uid,anchorid,liveid,sum(spend) as intimacy')->with('user')->group('uid')->order('intimacy desc')->select();
        return self::bulidSuccess($contribute);
    }

    public function getGoodsList(){
        $list = ShopGoodsModel::where(['shopid'=>Request::param('anchorid'),'status'=>1])->order(['live_explaining'=>'desc','id'=>'desc'])->select();
        return self::bulidSuccess($list);
    }

    public function explainingGoods(){
        $goodsid = Request::param('goodsid');
        $type = Request::param('type');
        $goods = ShopGoodsModel::where(['shopid'=>$this->userinfo->id,'status'=>1,'id'=>$goodsid])->find();
        if (!$goods){
            return self::bulidFail('商品信息有误');
        }
        if ($type == 1){
            ShopGoodsModel::update(['live_explaining'=>0],['shopid'=>$this->userinfo->id]);
            $goods->live_explaining = 1;
            if ($goods->save()){
                return self::bulidSuccess();
            }
        }else{
            $goods->live_explaining = 0;
            if ($goods->save()){
                return self::bulidSuccess();
            }
        }
        return self::bulidFail();
    }

    public function checkIsMgr(){
        $anchorid = Request::param('anchorid');
        $mgr = LiveRoomManagerModel::where(['anchorid'=>$anchorid,'mgrid'=>$this->userinfo->id])->find();
        $is_mgr = 0;
        if ($mgr){
            $is_mgr = 1;
        }
        return self::bulidSuccess(['is_mgr'=>$is_mgr]);
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
        return self::bulidSuccess($users);
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

    public function setLinkOnOff(){
        $type = Request::param('type');
        $live = LiveModel::where(['anchorid'=>$this->userinfo->id])->find();
        if (!$live){
            return self::bulidFail();
        }
        if (($type == 1 && $live->link_on == 1) || ($type == 0 && $live->link_on == 0)){
            self::sendLinkOnOffNotify($this->userinfo->id,$type);
            return self::bulidSuccess();
        }
        if ($live->save(['link_on'=>$type])){
            self::sendLinkOnOffNotify($this->userinfo->id,$type);
            return self::bulidSuccess();
        }
        return self::bulidFail();
    }

    private static function sendLinkOnOffNotify($anchorid,$ison = 0){
        $type = $ison?"RoomNotifyTypeLinkOn":"RoomNotifyTypeLinkOff";
        $im_ext['notify'] = ['type'=>$type];
        $elem = TXService::buildCustomElem('RoomNotification',$im_ext);
        TXService::sendChatRoomMsg(getAnchorRoomID($anchorid),$elem);
    }

    // 查询用户补充信息
    public function getGroupUserData(){
        $user_id = Request::post('user_id');
        $anchor_id = $this->userinfo->id;
        $user_ip = UserModel::where(['id'=>$user_id])->value('ip');
        $is_admin = LiveRoomManagerModel::where(['anchorid'=>$anchor_id,'mgrid'=>$user_id])->value('id') ? 1 : 0;
        $is_forbid_send_msg = LiveRoomBanneduserModel::where(['anchorid'=>$anchor_id,'uid'=>$user_id])->value('id') ? 1 : 0;
        $data = [
            'user_ip' => $user_ip,
            'is_admin' => $is_admin,
            'is_forbid_send_msg' => $is_forbid_send_msg
        ];
        return self::bulidSuccess($data);
    }

}