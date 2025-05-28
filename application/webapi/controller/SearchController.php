<?php


namespace app\webapi\controller;


use app\common\model\LiveModel;
use app\common\model\UserModel;
use think\facade\Request;

class SearchController extends BaseController
{
    public function main(){
        $keyword = Request::param('keyword');
        $anchors = UserModel::whereRaw("nick_name like '%{$keyword}%'")->where('status', 0)->order(['online_status'=>'asc', 'rec_weight'=>'desc', 'anchor_point'=>'desc', 'regist_time'=>'desc'])->with(['profile','live'])->field(["id, nick_name, avatar, anchor_level, online_status"])->limit(0,7)->select();

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

        $lives = LiveModel::whereRaw("title like '%{$keyword}%'")->with(['anchor'])->order(['rec_weight'=>'desc','hot'=>'desc','start_stamp'=>'desc'])->limit(0,10)->select();

        return self::bulidSuccess(['anchors'=>$anchors,'lives'=>$lives]);
    }

    public function anchor(){
        $keyword = Request::param('keyword');
        $page = Request::param('page') ?? 1;
        $size = Request::param('size') ?? 20;
        $anchors = UserModel::whereRaw("nick_name like '%{$keyword}%'")->where('status', 0)->order(['online_status'=>'asc', 'rec_weight'=>'desc', 'anchor_point'=>'desc', 'regist_time'=>'desc'])->with(['profile','live'])->field(["id, nick_name, avatar, anchor_level, online_status"])->limit(($page-1)*$size,$size)->select();

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

        $count = UserModel::whereRaw("nick_name like '%{$keyword}%'")->where('status', 0)->count('id');

        return self::bulidSuccess(['anchors'=>$anchors,'count'=>$count]);
    }

    public function live(){
        $keyword = Request::param('keyword');
        $page = Request::param('page') ?? 1;
        $size = Request::param('size') ?? 20;
        $lives = LiveModel::whereRaw("title like '%{$keyword}%'")->with(['anchor'])->order(['rec_weight'=>'desc','hot'=>'desc','start_stamp'=>'desc'])->limit(($page-1)*$size,$size)->select();

        $count = LiveModel::whereRaw("title like '%{$keyword}%'")->count('*');

        return self::bulidSuccess(['lives'=>$lives,'count'=>$count]);
    }
}