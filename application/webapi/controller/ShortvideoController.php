<?php


namespace app\webapi\controller;


use app\common\model\ShortvideoModel;
use think\facade\Request;

class ShortvideoController extends BaseController
{
    public function getShortvideoInfo(){
        $id = Request::param('id');
        $video = ShortvideoModel::where(['id'=>$id])->with('author')->find();
        return self::bulidSuccess($video);
    }
}