<?php
/**
 * Created by PhpStorm.
 * User: yun7_mac
 * Date: 2020-01-02
 * Time: 14:34
 */

namespace app\admin\controller;


use app\common\model\MomentModel;
use app\common\model\MomentReportModel;
use app\common\model\UserModel;
use think\facade\Request;

class MomentController extends BaseController
{
    public function index(){
        return $this->fetch();
    }

    public function add(){
        return $this->fetch();
    }

    public function edit(){
        $id = Request::param('id');
        $moment = MomentModel::get($id);
        $this->assign('momentinfo', $moment);
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
        $moments = MomentModel::where(['is_secret'=>0])->where($where)->with(['user'])->limit(($page-1)*$limit,$limit)->order('recommend','desc')->order('create_time', 'desc')->select();
        if (count($moments)>0){
            $count = MomentModel::where(['is_secret'=>0])->where($where)->count();
            return json(["code"=>0,"msg"=>"","data"=>$moments,"count"=>$count]);
        }
        return self::bulidFail("未查询到相关数据");
    }

    public function add_post(){
        $uid = Request::param('uid');
        $type = Request::param('type');
        $title = Request::param('title');
        $image_url = Request::param('image_url');
        $blur_image_url = Request::param('blur_image_url');
        $video_url = Request::param('video_url');
        $single_display_type = Request::param('single_display_type');
        $unlock_price = Request::param('unlock_price');
        if ($type == 2 && !$image_url){
            return self::bulidFail('图片类动态请先上传图片');
        }
        if ($type == 3 && !$video_url){
            return self::bulidFail('视频类动态请先上传视频');
        }
        $user = UserModel::get($uid);
        if (!$user){
            return self::bulidFail('用户不存在');
        }
        $moment = new MomentModel(['uid'=>$uid, 'type'=>$type, 'title'=>$title, 'image_url'=>$image_url, 'video_url'=>$video_url, 'unlock_price'=>$unlock_price, 'create_time'=>nowFormat()]);
        if ($type == 2){
            $imgArr = explode(',',$image_url);
            if ($imgArr && count($imgArr) == 1){
                if (!$single_display_type){
                    return self::bulidFail('单图动态请选择展示方式');
                }
                $moment->single_display_type = $single_display_type;
            }
            if ($unlock_price > 0 && !$blur_image_url){
                $blur_img_arr = [];
                foreach ($imgArr as $img_url){
                    $blur_img_arr[] = str_replace('https','http',str_replace('cos','pic',$img_url)).'?imageMogr2/blur/50x5';
                }
                $blur_image_url = implode(',',$blur_img_arr);
                $moment->blur_image_url = $blur_image_url;
            }
        }
        if ($type == 3){
            if (!$single_display_type){
                return self::bulidFail('视频类动态请选择展示方式');
            }
            if (!$image_url){
                return self::bulidFail('视频类动态请上传视频封面');
            }
            $moment->single_display_type = $single_display_type;
            if ($unlock_price > 0 && !$blur_image_url){
                $blur_image_url = str_replace('https','http',str_replace('cos','pic',$image_url)).'?imageMogr2/blur/50x5';
                $moment->blur_image_url = $blur_image_url;
            }
        }
        //管理员发动态默认审核通过
        $moment->status = 1;
        if ($moment->save()){
            return self::bulidSuccess([]);
        }else{
            return self::bulidFail();
        }
    }

    public function edit_post(){
        $id = Request::param('id');
        $moment = MomentModel::where(['id'=>$id])->find();
        if ($moment->status != 1 && Request::param('status') == 1){
            //审核通过 加经验
            $user = UserModel::where(['id'=>$moment->uid])->find();
            $userpoint = $user->point + addUserPointEventPublishMoment();
            $user->point = ['inc', addUserPointEventPublishMoment()];
            //更新用户等级
            $user->user_level = calculateUserLevel($userpoint);
            $user->save();
        }
        if (MomentModel::update(Request::param(),['id'=>$id])){
            return self::bulidSuccess([]);
        }
        return self::bulidFail();
    }

    public function del_post(){
        $id = Request::param('id');
        $status = Request::param("status");
        if (MomentModel::update(['status'=>$status],['id'=>$id])){
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
        $res = MomentReportModel::with(['user','moment'])->order('create_time','desc')->limit(($page-1)*$limit,$limit)->select();
        if (count($res)>0){
            $count = MomentReportModel::count('id');
            return json(["code"=>0,"msg"=>"","data"=>$res,"count"=>$count]);
        }
        return self::bulidFail("未查询到相关数据");
    }
}