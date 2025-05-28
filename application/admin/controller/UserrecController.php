<?php
/**
 * Created by PhpStorm.
 * User: yun7_mac
 * Date: 2019-12-09
 * Time: 09:40
 */

namespace app\admin\controller;


use app\common\model\AdminLogModel;
use app\common\model\UserModel;
use app\common\model\UserRecModel;
use think\Db;
use think\facade\Request;

class UserrecController extends BaseController
{
    public function index(){
        return $this->fetch();
    }

    public function add(){
        return $this->fetch();
    }

    public function getlist(){
        $where = [];
        $whereTimeEnd = '';

        $uid = Request::param('uid');
        $end_time = Request::param("end_time");
        $status = Request::param("status");

        if ($uid){
            $where["uid"] = $uid;
        }
        if ($status != null){
            switch ($status){
                case 0:
                    $where["start_status"] = 0; //未生效
                    break;
                case 1:
                    $where["start_status"] = 1; //已生效
                    $where["end_status"] = 0;
                    break;
                case 2:
                    $where["end_status"] = 1; //已过期
                    break;
            }
        }

        if ($end_time){
            $whereTimeEnd = "end_time >= '{$end_time}'";
        }
        $page = Request::param("page");
        $limit = Request::param("limit");
        $rec = UserRecModel::where($where)->where($whereTimeEnd)->withJoin(['user'=>function($query){
            $query->withField('nick_name, rec_weight');
        }])->limit(($page-1)*$limit,$limit)->order("start_time","desc")->select();
        if (count($rec)>0){
            $count = UserRecModel::where($where)->where($whereTimeEnd)->count("id");
            return json(["code"=>0,"msg"=>"","data"=>$rec,"count"=>$count]);
        }
        return self::bulidFail("未查询到相关数据");
    }

    public function add_post(){
        $uid = Request::param("uid");
        $rec_weight = Request::param("rec_weight");
        $start = Request::param("start_time");
        $end = Request::param("end_time");

        $recModel = new UserRecModel();
        $recModel->uid = $uid;
        $recModel->rec_weight = $rec_weight;
        $recModel->start_time = $start?$start:date("Y-m-d H:i:s");
        $recModel->end_time = $end;

        $userModel = UserModel::get($uid);
        if (!$userModel){
            return self::bulidFail("用户不存在");
        }

        if (!$start){
            //立即生效
            $recModel->start_status = 1;

            Db::startTrans();

            if ($userModel->rec_weight > $rec_weight){
                return self::bulidFail('该主播已有权重更高的推广任务正在执行中');
            }
            if ($userModel->save(["rec_weight"=>$rec_weight]) && $recModel->save()){
                Db::commit();
                return self::bulidSuccess([]);
            }else {
                Db::rollback();
                return self::bulidFail();
            }
        }else{
            if ($recModel->save()){
                return self::bulidSuccess([]);
            }
            return self::bulidFail();
        }
    }

    public function start_post(){
        $id = Request::param("id");
        $recModel = UserRecModel::get($id);

        if (!$recModel){
            return self::bulidFail("所选任务不存在");
        }
        if (strtotime($recModel->end_time) < time()){
            return self::bulidFail("所选任务已截止");
        }

        $userModel = UserModel::get($recModel->uid);
        if (!$userModel){
            return self::bulidFail("用户不存在");
        }

        if ($userModel->rec_weight > $recModel->rec_weight){
            return self::bulidFail('该主播已有权重更高的推广任务正在执行中');
        }

        Db::startTrans();
        if ($userModel->save(["rec_weight"=>$recModel->rec_weight]) && $recModel->save(['start_status'=>1])){
            Db::commit();
            $adminlog = new AdminLogModel(["adminid"=>$this->userinfo["id"],"content"=>$this->userinfo["name"]."将用户".$userModel->nick_name."(".$userModel->id.")"."的推广任务ID：".$recModel->id."状态修改为已生效", "create_time"=>date("Y-m-d H:i:s")]);
            $adminlog->save();
            return self::bulidSuccess([]);
        }else {
            Db::rollback();
            return self::bulidFail();
        }
    }

    public function end_post(){
        $id = Request::param("id");
        $recModel = UserRecModel::get($id);

        if (!$recModel){
            return self::bulidFail("所选任务不存在");
        }
        if ($recModel->end_status == 1){
            return self::bulidFail("所选任务状态异常");
        }

        if ($recModel->start_status != 1){
            return self::bulidFail("所选任务尚未生效");
        }

        $userModel = UserModel::get($recModel->uid);
        if (!$userModel){
            return self::bulidFail("用户不存在");
        }

        if ($userModel->save(["rec_weight"=>0])){
            if ($recModel->save(['end_status'=>1])){
                Db::commit();
                $adminlog = new AdminLogModel(["adminid"=>$this->userinfo["id"],"content"=>$this->userinfo["name"]."将用户".$userModel->nick_name."(".$userModel->id.")"."的推广任务ID：".$recModel->id."状态修改为已结束", "create_time"=>date("Y-m-d H:i:s")]);
                $adminlog->save();
                return self::bulidSuccess([]);
            }else{
                Db::rollback();
                return self::bulidFail();
            }
        }else {
            Db::rollback();
            return self::bulidFail();
        }
    }
}