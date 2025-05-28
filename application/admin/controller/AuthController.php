<?php
/**
 * Created by PhpStorm.
 * User: yun7_mac
 * Date: 2020-02-06
 * Time: 11:28
 */

namespace app\admin\controller;


use app\common\model\SystemMsgModel;
use app\common\model\UserAuthModel;
use app\common\model\UserModel;
use app\common\TXService;
use think\Db;
use think\facade\Request;

class AuthController extends BaseController
{

    public function index(){
        return $this->fetch();
    }

    public function getlist(){
        $uid = Request::param("uid");
        $status = Request::param("status");
        $where = [];
        if ($uid){
            $where['uid'] = $uid;
        }
        if ($status != ''){
            $where['status'] = $status;
        }
        $page = Request::param("page");
        $limit = Request::param("limit");
        $auths = UserAuthModel::where($where)->with(['user'])->limit(($page-1)*$limit,$limit)->order('submit_time','desc')->select();
        if (count($auths)>0){
            $count = UserAuthModel::where($where)->count();
            return json(["code"=>0,"msg"=>"","data"=>$auths,"count"=>$count]);
        }
        return self::bulidFail("未查询到相关数据");
    }

    public function check(){
        $uid = Request::param('uid');
        $status = Request::param('status');
        $reject_reason = Request::param('reject_reason');
        $auth = UserAuthModel::where('uid',$uid)->find();
        $is_anchor = $status == 1?1:0;
        $user = new UserModel();
        Db::startTrans();
        if($auth->save(['status'=>$status,'reject_reason'=>$reject_reason,'check_time'=>nowFormat()]) && $user->save(['is_anchor'=>$is_anchor],['id'=>$uid])){
            //创建主播聊天室
            if ($status == 1){
                $createRoom = TXService::createChatRoom($uid,getAnchorRoomID($uid),'AVChatRoom');
                if ($createRoom['ActionStatus'] == 'OK' || $createRoom['ErrorCode'] == 10021){
                    Db::commit();
                    self::echoSuccess();
                    $title = '主播申请审核通过';
                    $content = "您的主播申请已审核通过，快去开启直播吧";
                    $stmmsgModel = new SystemMsgModel(['touid'=>$uid, 'title'=>$title, 'content'=>$content, 'create_time'=>nowFormat()]);
                    if ($stmmsgModel->save()){
                        TXService::sendSystemMsg([$uid],TXService::buildCustomElem('SystemMessage',[],'系统消息'));
                    }
                    exit();
                }else{
                    Db::rollback();
                    return self::bulidFail($createRoom["ErrorInfo"]);
                }
            }else{
                Db::commit();
                self::echoSuccess();
                $title = '主播申请被驳回';
                $content = "您的主播申请被驳回，驳回原因：{$reject_reason}";
                $stmmsgModel = new SystemMsgModel(['touid'=>$uid, 'title'=>$title, 'content'=>$content, 'create_time'=>nowFormat()]);
                if ($stmmsgModel->save()){
                    TXService::sendSystemMsg([$uid],TXService::buildCustomElem('SystemMessage',[],'系统消息'));
                }
                exit();
            }
        }else{
            Db::rollback();
            return self::bulidFail();
        }
    }
}