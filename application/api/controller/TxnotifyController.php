<?php
/**
 * Created by PhpStorm.
 * User: yun7_mac
 * Date: 2019-12-24
 * Time: 18:11
 */

namespace app\api\controller;


use app\common\model\AnchorIncomeModel;
use app\common\model\GiftLogModel;
use app\common\model\GuildModel;
use app\common\model\GuildProfitModel;
use app\common\model\LiveAudienceModel;
use app\common\model\LiveHistoryModel;
use app\common\model\LiveModel;
use app\common\model\LivePkModel;
use app\common\model\StatisticsModel;
use app\common\model\UserModel;
use app\common\model\UserOnlinetimeModel;
use app\common\TXService;
use think\Db;
use think\facade\Env;
use think\facade\Request;
use think\Controller;

class TxnotifyController extends Controller
{
    public function IMNotify(){
        $configPri = getConfigPri();
        $sdkappid = Request::param("SdkAppid");
        if ($sdkappid != $configPri->im_sdkappid){
            return json(["ActionStatus"=>"FAIL", "ErrorCode"=>0, "ErrorInfo"=>""]);
        }
        $command = Request::param('CallbackCommand');

        file_put_contents(Env::get('runtime_path') . 'IMNotify'.date('Y-m-d').'.txt',date('Y-m-d H:i:s').':'.json_encode(Request::param())."\r\n",FILE_APPEND);

        if ($command == 'State.StateChange'){
            //状态变更回调
            $action = Request::param('Info')["Action"];
            $uid = Request::param('Info')["To_Account"];
            $userModel = UserModel::where(['id'=>$uid])->find();
            if ($action == 'Login'){
                if ($userModel){
                    if (date('Ymd',strtotime($userModel->last_online)) != date('Ymd')){
                        //用户每日第一次登录 增加日活用户数量统计+1
                        $platform = strtoupper(Request::param('OptPlatform')); //Android、iOS、Web
                        $statistics = StatisticsModel::where(['id'=>intval(date('Ymd'))])->find();
                        if (!$statistics){
                            $statistics = new StatisticsModel(['id'=>intval(date('Ymd')),'time'=>nowFormat()]);
                        }
                        if ($platform == 'IOS'){
                            $statistics->activity_ios = ['inc',1];
                        }elseif ($platform == 'ANDROID'){
                            $statistics->activity_android = ['inc',1];
                        }
                        $statistics->save();
                        //用户每日第一次登录 增加经验值
                        $userpoint = $userModel->point + addUserPointEventLogin();
                        $userModel->point = ['inc', addUserPointEventLogin()];
                        //更新用户等级
                        $userModel->user_level = calculateUserLevel($userpoint);
                    }
                    $userModel->online_status = 1;
                    $userModel->last_online = nowFormat();
                    $userModel->save();
                    file_put_contents(Env::get('runtime_path') . 'IMNotify_Login_'.date('Y-m-d').'.txt',date('Y-m-d H:i:s').':'.json_encode(Request::param())."\r\n",FILE_APPEND);
                }
            }elseif ($action == "Logout" || $action== "Disconnect"){
                if ($userModel){
                    //统计在线时长
                    $userOnlinetimeModel = UserOnlinetimeModel::where(['uid'=>$uid])->find();
                    if (!$userOnlinetimeModel){
                        $userOnlinetimeModel = new UserOnlinetimeModel(['uid'=>$uid]);
                    }
                    $onlinetime = time() - strtotime($userModel->last_online);
                    $userOnlinetimeModel->total = ['inc',$onlinetime];
                    $userOnlinetimeModel->month = ['inc',$onlinetime];
                    $userOnlinetimeModel->save();

                    $userModel->online_status = 9;
                    $userModel->last_online = nowFormat();
                    $userModel->save();
                    file_put_contents(Env::get('runtime_path') . 'IMNotify_Logout_'.date('Y-m-d').'.txt',date('Y-m-d H:i:s').':'.json_encode(Request::param())."\r\n",FILE_APPEND);
                }
                $roomRes = TXService::getMemberGroups($uid,'ChatRoom');
                file_put_contents(Env::get('runtime_path') . 'GetMemberGroups_'.date('Y-m-d').'.txt',date('Y-m-d H:i:s').':'.json_encode($roomRes)."\r\n",FILE_APPEND);
                if ($roomRes['ActionStatus']=="OK"){
                    $rooms = $roomRes["GroupIdList"];
                    foreach ($rooms as $room){
                        $del_res = TXService::deleteGroupMember($room["GroupId"],[$uid]);
                        if ($del_res['ActionStatus'] != "OK"){
                            file_put_contents(Env::get('runtime_path') . 'GetMemberGroups_'.date('Y-m-d').'.txt',date('Y-m-d H:i:s').':deleteGroupMember:'.$room["GroupId"]."({$uid}):".json_encode($del_res)."\r\n",FILE_APPEND);
                        }
                    }
                }
                self::checkClose($uid);
            }
        }elseif ($command == 'Group.CallbackAfterNewMemberJoin' || $command == 'Group.CallbackAfterMemberExit'){
            $groupid = Request::param('GroupId');
            $anchorid = getAnchorIDByGroup($groupid);

            if ($command == 'Group.CallbackAfterNewMemberJoin' && $anchorid){
                //写入观众表
                $audienceids = getLiveAudience($anchorid);
                foreach (Request::param('NewMemberList') as $member){
                    if (!in_array($member['Member_Account'],$audienceids)) {
                        $audienceids[] = $member['Member_Account'];
                    }
                }
                setLiveAudience($anchorid,$audienceids);
            }elseif ($command == 'Group.CallbackAfterMemberExit' && $anchorid){
                //移出观众表
                $audienceids = getLiveAudience($anchorid);
                $audienceids_del = [];
                foreach (Request::param('ExitMemberList') as $member){
                    $audienceids_del[] = $member['Member_Account'];
                }
                $audienceids = array_diff($audienceids,$audienceids_del);
                setLiveAudience($anchorid,$audienceids);
            }
            //通知群内成员
            $elem = TXService::buildCustomElem('LiveGroupMemberJoinExit');
            $res = TXService::sendChatRoomMsg($groupid,$elem);
//            file_put_contents(Env::get('runtime_path') . 'LiveGroupMemberJoinExit_'.date('Y-m-d').'.txt',date('Y-m-d H:i:s').':LiveGroupMemberJoinExit:'.$groupid.":".json_encode($res)."\r\n",FILE_APPEND);
        }
        return json(["ActionStatus"=>"OK", "ErrorCode"=>0, "ErrorInfo"=>""]);
    }

    //直播断流推送
    public function PushNotify(){
        $configPri = getConfigPri();
        $appid = Request::param("appid");
        if ($appid != $configPri->qcloud_appid){
            return json(["ActionStatus"=>"FAIL", "ErrorCode"=>0, "ErrorInfo"=>""]);
        }
        $stream_id = Request::param('stream_id');
        if (self::checkClose($stream_id,1)){
            return json(['code'=>0]);
        }
        return json(['code'=>1]);
    }

    public static function checkClose($param, $type = 0){

        //type = 0 用户IM离线 1-推流断开
        if ($type == 0){
            $uid = $param;
            $live = LiveModel::where(['anchorid'=>$uid])->find();
            if (!$live){
                return true;
            }
            //检测推流是否断开
            if (TXService::checkPushActive($live->stream)){
                //推流未断 不结束直播
                return false;
            }
        }elseif ($type == 1){
            $stream = $param;
            $live = LiveModel::where(['stream'=>$stream])->find();
            if (!$live){
                return true;
            }
            //检测用户在线状态
            $anchor = UserModel::where(['id'=>$live->anchorid])->field(['online_status'])->find();
            if ($anchor->online_status != 9){
                return true;
            }
        }

        Db::startTrans();
        $anchor = UserModel::where(['id'=>$uid])->find();

        $liveHistory = new LiveHistoryModel(['anchorid'=>$uid,'liveid'=>$live->liveid,'title'=>$live->title,'stream'=>$live->stream,'pull_url'=>$live->pull_url,'categoryid'=>$live->categoryid,'orientation'=>$live->orientation,'start_stamp'=>$live->start_stamp,'end_stamp'=>time(),'start_time'=>$live->start_time,'end_time'=>nowFormat(),'gift_profit'=>$live->profit]);

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
        $anchorIncome = AnchorIncomeModel::where(['anchorid'=>$uid])->whereTime('date','today')->find();
        if (!$anchorIncome){
            $anchorIncome = new AnchorIncomeModel(['anchorid'=>$uid,'income'=>0,'date'=>date('Y-m-d')]);
        }
        $anchorIncome->income = ['inc',$live->profit];

        if ($live->delete() && $liveHistory->save() && $anchorIncome->save() && $guildup) {
            Db::commit();
            //清空redis观众列表
            setLiveAudience($anchor->id,[]);
            file_put_contents(Env::get('runtime_path') . 'EndLive_'.date('Y-m-d').'.txt',date('Y-m-d H:i:s').":结束直播:{$live->liveid}({$uid}):\r\n",FILE_APPEND);
            //通知群内成员
            $endelem = TXService::buildCustomElem('LiveFinished');
            TXService::sendChatRoomMsg(getAnchorRoomID($uid),$endelem);
            //结束pk
            if ($live->pkid){
                $pkinfo = LivePkModel::where(['id'=>$live->pkid])->find();
                $pkanchorid = $pkinfo->home_anchorid == $liveHistory->anchorid ? $pkinfo->away_anchorid : $pkinfo->home_anchorid;
                if (LiveModel::where(['anchorid'=>$pkanchorid])->update(['pkid'=>0,'pk_status'=>0])){
                    $elem = TXService::buildCustomElem('RoomNotification',['type'=>'RoomNotifyTypePkEnd']);
                    TXService::sendChatRoomMsg(getAnchorRoomID($pkanchorid),$elem);
                }
            }
            return true;
        }else{
            file_put_contents(Env::get('runtime_path') . 'EndLive_'.date('Y-m-d').'.txt',date('Y-m-d H:i:s').":结束直播失败:{$live->liveid}({$uid}):\r\n",FILE_APPEND);
            Db::rollback();
        }
        return false;
    }
}