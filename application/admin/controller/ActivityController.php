<?php


namespace app\admin\controller;


use app\common\model\ActivityModel;
use think\facade\Env;
use think\facade\Request;

class ActivityController extends BaseController
{
    public function index(){
        $webUri = getConfigPub()->site_domain.'/activity';
        $this->assign('webUri',$webUri);
        return $this->fetch();
    }

    public function add(){
        return $this->fetch();
    }

    public function edit(){
        $id = Request::param('id');
        $data = ActivityModel::where(['id'=>$id])->find();
        $this->assign('data',$data);
        return $this->fetch();
    }

    public function getactivitys(){
        $page = Request::param("page");
        $limit = Request::param("limit");
        $where = [];
        if ($type = Request::param('type')){
            $where['type'] = $type;
        }
        $list = ActivityModel::where($where)->order('create_time','des')->limit(($page-1)*$limit,$limit)->select();
        if (count($list) > 0){
            $count = ActivityModel::where($where)->count("id");
            return json(["code"=>0,"msg"=>"","data"=>$list,"count"=>$count]);
        }
        return self::bulidFail("未查询到相关数据");
    }

    public function add_post(){
        $activity = new ActivityModel(Request::param());
        if ($activity->type == 1 && (!$activity->start_time || !$activity->end_time)){
            return self::bulidFail('请输入活动起止时间');
        }
        $activity->create_time = nowFormat();
        $activity->modify_time = nowFormat();
        if ($activity->save()){
            return self::bulidSuccess();
        }
        return self::bulidFail();
    }

    public function edit_post(){
        if (Request::param('type') == 1 && (!Request::param('start_time') || !Request::param('end_time'))){
            return self::bulidFail('请输入活动起止时间');
        }

        $id = Request::param('id');
        $activity = ActivityModel::where(['id'=>$id])->find();
        if ($activity->save(Request::except('id'))){
            return self::bulidSuccess();
        }
        return self::bulidFail();
    }

    public function del_post(){
        $id = Request::param('activityid');
        if (ActivityModel::where(['id'=>$id])->delete()){
            return self::bulidSuccess();
        }
        return self::bulidFail();
    }

    public function uploadImage(){
        $img = Request::file('file');
        if (!empty($img)){
            $filePaths = Env::get('root_path') . 'public' . DIRECTORY_SEPARATOR . "uploads/image/activity";
            if(!file_exists($filePaths)){
                mkdir($filePaths,0777,true);
            }
            $info = $img->validate(['ext' => 'jpg,png,jpeg'])->move($filePaths);
            $filename = $info->getSaveName();
            if ($filename) {
                if ($_SERVER['HTTP_HOST'] && $_SERVER['HTTP_HOST'] != 'localhost'){
                    $src = 'http://'.$_SERVER['HTTP_HOST']."/uploads/image/activity/" . $filename;
                }else{
                    $src = "/uploads/image/activity/" . $filename;
                }
                return json(['code'=>0,'data'=>['src'=>$src]]);
            }else{
                return json(["code" => 1, "msg" => "只能上传jpg,png,jpeg格式图片"]);
            }
        }
        return json(['code'=>1, 'msg'=>'上传失败']);
    }
}