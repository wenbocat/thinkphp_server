<?php


namespace app\admin\controller;


use app\common\model\TopicModel;
use think\facade\Request;

class TopicController extends BaseController
{
    public function index(){
        return $this->fetch();
    }

    public function add(){
        return $this->fetch();
    }

    public function edit(){
        $topic = TopicModel::where(['id'=>Request::param('id')])->find();
        $this->assign('topic',$topic);
        return $this->fetch();
    }

    public function getlist(){
        $page = Request::param("page");
        $limit = Request::param("limit");
        $list = TopicModel::order(['create_time'=>'desc'])->limit(($page-1)*$limit,$limit)->select();
        if (count($list)>0){
            $count = TopicModel::count("id");
            return json(["code"=>0,"msg"=>"","data"=>$list,"count"=>$count]);
        }
        return self::bulidFail("未查询到相关数据");
    }

    public function add_post(){
        $topic = new TopicModel(Request::param());
        $topic->create_time = nowFormat();
        if ($topic->save()){
            return self::bulidSuccess();
        }
        return self::bulidFail();
    }

    public function edit_post(){
        $topic = TopicModel::where(['id'=>Request::param('id')])->find();
        if ($topic->save(Request::param())){
            return self::bulidSuccess();
        }
        return self::bulidFail();
    }

    public function del_post(){
        $topic = TopicModel::where(['id'=>Request::param('id')])->find();
        if ($topic->save(Request::param())){
            return self::bulidSuccess();
        }
        return self::bulidFail();
    }
}