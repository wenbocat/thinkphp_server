<?php


namespace app\api\controller;


use app\common\model\TopicModel;
use think\facade\Request;

class TopicController extends BaseController
{
    /**
     * 需要登录的方法
     */
    protected $NeedLogin = [];

    /**
     * 参数校验规则
     */
    protected $rules = [
        'gettopicinfo'=>[
            'topic'=>'require',
        ],
    ];

    public function getTopicList(){
        $page = Request::param('page') ?? 1;
        $size = Request::param('size') ?? 20;
        $keyword = Request::param('keyword');
        if ($keyword){
            $where = "title like '%{$keyword}%'";
            $list = TopicModel::where(['status'=>1])->whereRaw($where)->limit(($page-1)*$size,$size)->order(['used_times'=>'desc'])->select();
        }else{
            $list = TopicModel::where(['status'=>1])->limit(($page-1)*$size,$size)->order(['used_times'=>'desc'])->select();
        }
        return self::bulidSuccess($list);
    }

    public function getTopicInfo(){
        $title = Request::param('topic');
        $topic = TopicModel::where(['title'=>str_replace('#','',$title)])->find();
        if ($topic) {
            return self::bulidSuccess($topic);
        }
        return self::bulidFail();
    }
}