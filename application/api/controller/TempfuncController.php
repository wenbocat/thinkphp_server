<?php


namespace app\api\controller;


use app\common\model\AgentModel;
use app\common\model\GiftLogModel;
use app\common\model\LiveModel;
use app\common\model\LivePkModel;
use app\common\model\UserModel;
use app\common\model\UserProfileModel;
use app\common\NSLog;
use app\common\TXService;
use think\Db;
use think\facade\Env;
use think\facade\Request;

class TempfuncController extends BaseController
{
    public function createRoom()
    {
        $uids = ['63621131'];
        $rs = [];
        foreach ($uids as $uid){
            $r = TXService::createChatRoom('admin',getAnchorRoomID($uid),'AVChatRoom');
            $rs[] = $r;
        }
        return json($rs);
    }

    public function createUserProfile(){
        $users = UserModel::select();
        foreach ($users as $user){
            $profile = new UserProfileModel(['uid'=>$user->id,'age'=>22,"gender"=>0,'career'=>'学生','height'=>168,'weight'=>46,'city'=>'上海','birthday'=>'01-18','constellation'=>'处女座','signature'=>'小哥哥我在等你哦😃']);
            $profile->save();
        }
        return self::bulidSuccess();
    }

    public function createInviteCode(){
        $agents = AgentModel::select();
        foreach ($agents as $agent){
            if (!$agent->invite_code){
                AgentModel::update(['invite_code'=>createInviteCode()],['uid'=>$agent->uid]);
            }
        }
        return self::bulidSuccess($agents);
    }

    public function autoInsertGiftLog(){
        $userids = [63621147,63621148,63621150,63621151,63621152,63621153];
        $lives = LiveModel::select();
        foreach ($lives as $live){
            foreach ($userids as $uid){
                $log = new GiftLogModel(['giftid'=>19,'anchorid'=>$live->anchorid,'uid'=>$uid,'liveid'=>$live->liveid,'count'=>1,'spend'=>rand(60,500),'create_time'=>nowFormat()]);
                $log->save();
            }
        }
    }

    public function autoEnterPk(){
        $anchorid = 63621152;
        $pkanchorid = 63621131;
        Db::startTrans();
        $home_anchorid = rand(0,1) == 1 ? $anchorid:$pkanchorid;
        $away_anchorid = $home_anchorid == $anchorid ? $pkanchorid:$anchorid;
        $pkModel = new LivePkModel(['home_anchorid'=>$home_anchorid,'away_anchorid'=>$away_anchorid,'home_score'=>rand(0,99),'away_score'=>rand(0,99),'create_time'=>nowFormat()]);
        if ($pkModel->save()){
            $pkdata = ['pk_status'=>1,'pkid'=>$pkModel->id];
            if (LiveModel::where(['anchorid'=>$anchorid])->update($pkdata) && LiveModel::where(['anchorid'=>$pkanchorid])->update($pkdata)){
                //开始pk
                Db::commit();
                self::echoSuccess();
                //推送至双方直播间
                $home_live = LiveModel::where(['anchorid'=>$home_anchorid])->find();
                $im_ext_home['notify'] = ['type'=>'RoomNotifyTypePkStart','pklive'=>$home_live,'pkinfo'=>$pkModel];
                $elem_home = TXService::buildCustomElem('RoomNotification',$im_ext_home);
                $result_home = TXService::sendChatRoomMsg(getAnchorRoomID($away_anchorid),$elem_home);
                NSLog::writeRuntimeLog('RoomNotifyTypePkStart',$result_home);

                $away_live = LiveModel::where(['anchorid'=>$away_anchorid])->find();
                $im_ext_away['notify'] = ['type'=>'RoomNotifyTypePkStart','pklive'=>$away_live,'pkinfo'=>$pkModel];
                $elem_away = TXService::buildCustomElem('RoomNotification',$im_ext_away);
                $result_away = TXService::sendChatRoomMsg(getAnchorRoomID($home_anchorid),$elem_away);
                NSLog::writeRuntimeLog('RoomNotifyTypePkStart',$result_away);
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

    public function streamerPush(){
        //调用IM推送
//        $user = UserModel::where(['id'=>63621147])->find();
//        $pushids = ['63621151','63621170'];
//        $elem = TXService::buildCustomElem('BroadcastStreamer',['streamer'=>['user'=>$user->visible(['nick_name','avatar','id']),'vip'=>['level'=>4],'type'=>1]],'全频道广播','');
//        $result = TXService::sendBroadcast($pushids,$elem);

        $user = UserModel::where(['id'=>63621147])->find();
        $users = UserModel::where(['online_status'=>1])->field('id')->select()->toArray();
        $uidArr = explode(',',implode(',',array_column($users,'id')));
        for ($i = 0; $i<count($uidArr);){
            $pushids = array_slice($uidArr, $i, 500);
            $i += 500;
            $elem = TXService::buildCustomElem('BroadcastStreamer',['streamer'=>['user'=>$user->visible(['nick_name','avatar','id']),'vip'=>['level'=>4],'type'=>1]],'全频道广播','');
            $result = TXService::sendBroadcast($pushids,$elem);
        }
        echo json_encode($result);
    }

    public function getImg(){
        $img_base64 = '';
        $img_file = Env::get('public_path') . 'images/admin/login_bg.png';
        if (file_exists($img_file)) {
            $app_img_file = $img_file; // 图片路径
            $img_info = getimagesize($app_img_file); // 取得图片的大小，类型等

            //echo '<pre>' . print_r($img_info, true) . '</pre><br>';
            $fp = fopen($app_img_file, "r"); // 图片是否可读权限

            if ($fp) {
                $filesize = filesize($app_img_file);
                $content = fread($fp, $filesize);
                $file_content = chunk_split(base64_encode($content)); // base64编码
                switch ($img_info[2]) {           //判读图片类型
                    case 1: $img_type = "gif";
                        break;
                    case 2: $img_type = "jpg";
                        break;
                    case 3: $img_type = "png";
                        break;
                }

//                $img_base64 = 'data:image/' . $img_type . ';base64,' . $file_content;//合成图片的base64编码
                $img_base64 = $file_content;//合成图片的base64编码

            }
            fclose($fp);
        }
        echo $img_base64; //返回图片的base64
    }

}