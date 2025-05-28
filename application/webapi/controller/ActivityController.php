<?php


namespace app\webapi\controller;


use app\common\model\ActivityModel;
use think\facade\Request;

class ActivityController extends BaseController
{
    public function getList(){
        $page = Request::param('page') ?? 1;
        $size = Request::param('size') ?? 10;
        $type = Request::param('type') ?? 1;
        $list = ActivityModel::where(['type'=>$type])->order(['modify_time'=>'desc'])->limit(($page-1)*$size,$size)->select();
        foreach ($list as $activity){
            $activity->content = mb_substr(strip_tags($activity->content),0,50);
        }
        $count = ActivityModel::where(['type'=>$type])->count('id');
        return self::bulidSuccess(['list'=>$list,'count'=>$count]);
    }

    public function activityInfo(){
        $id = Request::param('id');
        $activity = ActivityModel::where(['id'=>$id])->find();
        if ($activity){
            return self::bulidSuccess($activity);
        }
        return self::bulidFail('活动ID不存在');
    }
}