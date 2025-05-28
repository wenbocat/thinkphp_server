<?php
/**
 * Created by PhpStorm.
 * User: yun7_mac
 * Date: 2020-01-02
 * Time: 14:34
 */

namespace app\admin\controller;


use app\common\model\ShortvideoModel;
use app\common\model\ShortvideoReportModel;
use app\common\model\UserModel;
use think\facade\Request;

class ShortvideoController extends BaseController
{
    public function index(){
        return $this->fetch();
    }

    public function add(){
        return $this->fetch();
    }

    public function edit(){
        $id = Request::param('id');
        $shortvideo = ShortvideoModel::get($id);
        $this->assign('shortvideoinfo', $shortvideo);
        return $this->fetch();
    }

    public function report(){
        return $this->fetch();
    }

    public function getlist(){
        $id = Request::param("id");
        $uid = Request::param("uid");
        $status = Request::param("status");
        $where = [];
        if ($id){
            $where['id'] = $id;
        }
        if ($uid){
            $where['uid'] = $uid;
        }
        if ($status != ''){
            $where['status'] = $status;
        }
        $page = Request::param("page");
        $limit = Request::param("limit");
        $shortvideos = ShortvideoModel::where($where)->with(['author'])->limit(($page-1)*$limit,$limit)->order('create_time', 'desc')->select();
        if (count($shortvideos)>0){
            $count = ShortvideoModel::where($where)->count();
            return json(["code"=>0,"msg"=>"","data"=>$shortvideos,"count"=>$count]);
        }
        return self::bulidFail("未查询到相关数据");
    }

    public function add_post(){
        $uid = Request::param('uid');
        $title = Request::param('title');
        $thumb_url = Request::param('thumb_url');
        $play_url = Request::param('play_url');

        $user = UserModel::get($uid);
        if (!$user){
            return self::bulidFail('用户不存在');
        }
        $shortvideo = new ShortvideoModel(['uid'=>$uid, 'title'=>$title, 'thumb_url'=>$thumb_url, 'play_url'=>$play_url, 'create_time'=>nowFormat()]);
        //管理员发动态默认审核通过
        $shortvideo->status = 1;

        if ($shortvideo->save()){
            return self::bulidSuccess([]);
        }else{
            return self::bulidFail();
        }
    }

    public function edit_post(){
        $id = Request::param('id');
        $video = ShortvideoModel::where(['id'=>$id])->find();
        if ($video->status != 1 && Request::param('status') == 1){
            //审核通过 加经验
            $user = UserModel::where(['id'=>$video->uid])->find();
            $userpoint = $user->point + addUserPointEventPublishShortVideo();
            $user->point = ['inc', addUserPointEventPublishShortVideo()];
            //更新用户等级
            $user->user_level = calculateUserLevel($userpoint);
            $user->save();
        }
        if (ShortvideoModel::update(Request::param(),['id'=>$id])){
            return self::bulidSuccess([]);
        }
        return self::bulidFail();
    }

    public function del_post(){
        $id = Request::param('id');
        $status = Request::param("status");
        if (ShortvideoModel::update(['status'=>$status],['id'=>$id])){
            return self::bulidSuccess([]);
        }else{
            return self::bulidFail();
        }
    }

    public function signForVod(){
        $configPri = getConfigPri();
        // 确定签名的当前时间和失效时间
        $current = time();
        $expired = $current + 86400;  // 签名有效期：1天

        // 向参数列表填入参数
        $arg_list = array(
            "secretId" => $configPri['qcloud_secretid'],
            "currentTimeStamp" => $current,
            "expireTime" => $expired,
            "random" => rand(),
            "oneTimeValid" => 1);

        // 计算签名
        $orignal = http_build_query($arg_list);
        $signature = base64_encode(hash_hmac('SHA1', $orignal, $configPri['qcloud_secretkey'], true).$orignal);
        return self::bulidSuccess(['sign'=>$signature]);
    }

    public function getReportList(){
        $page = Request::param("page");
        $limit = Request::param("limit");
        $res = ShortvideoReportModel::with(['user','Shortvideo'])->order('create_time','desc')->limit(($page-1)*$limit,$limit)->select();
        if (count($res)>0){
            $count = ShortvideoReportModel::count('id');
            return json(["code"=>0,"msg"=>"","data"=>$res,"count"=>$count]);
        }
        return self::bulidFail("未查询到相关数据");
    }
}