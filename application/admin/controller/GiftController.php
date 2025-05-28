<?php
/**
 * Created by PhpStorm.
 * User: yun7_mac
 * Date: 2019-11-25
 * Time: 11:00
 */

namespace app\admin\controller;


use app\common\model\GiftModel;
use app\common\model\GiftLogModel;
use think\facade\Request;

class GiftController extends BaseController
{
    public function index(){
        return $this->fetch();
    }

    public function add(){
        return $this->fetch();
    }

    public function edit(){
        $id = Request::param('id');
        $giftinfo = GiftModel::get($id);
        $this->assign("giftinfo",$giftinfo);
        return $this->fetch();
    }

    public function signForCos()
    {
        return parent::signForCos();
    }

    public function getlist(){
        $gifts = GiftModel::order(['sort'=>'asc', 'id'=>'asc'])->select();
        if (count($gifts) > 0){
            return self::bulidSuccess($gifts);
        }else{
            return self::bulidFail("未查询到相关数据");
        }
    }

    public function add_post(){
        $gift = new GiftModel(Request::param());
        if ($gift->save() == 1){
            return self::bulidSuccess([]);
        }
        return self::bulidFail();
    }

    public function edit_post(){
        $id = Request::param("id");

        $giftModel = GiftModel::get($id);
        if (!$giftModel){
            return self::bulidFail("礼物信息不存在");
        }

        if ($giftModel->save(Request::param())){
            return self::bulidSuccess([],"编辑成功");
        }else{
            return self::bulidFail();
        }
    }

    //上架/下架
    public function del_post(){
        $giftModel = GiftModel::get(Request::param("id"));

        if (!$giftModel){
            return self::bulidFail("参数错误");
        }

        $giftModel->status = Request::param("status");

        if ($giftModel->save()){
            return self::bulidSuccess([],"操作成功");
        }else{
            return self::bulidFail();
        }
    }

    /////////////////////////////////////////     赠礼记录      ////////////////////////////////////////----

    public function sendlog(){
        return $this->fetch();
    }

    public function getsendlist(){
        $where = [];

        $id = Request::param("id");
        $uid = Request::param("uid");
        $anchorid = Request::param("anchorid");
        $liveid = Request::param('liveid');


        if ($id){
            $where["id"] = $id;
        }
        if ($uid){
            $where["uid"] = $uid;
        }
        if ($anchorid){
            $where["anchorid"] = $anchorid;
        }
        if ($liveid){
            $where["liveid"] = $liveid;
        }

        $page = Request::param("page");
        $limit = Request::param("limit");
        $giftSends = GiftLogModel::where($where)->with(['user' => function($query){
            $query->field('id, nick_name');
        },'anchor' => function($query){
            $query->field('id, nick_name');
        },'gift' => function($query){
            $query->field('id, title');
        }])->limit(($page-1)*$limit,$limit)->order("create_time","desc")->select();
        if (count($giftSends)>0){
            $count = GiftLogModel::where($where)->count("id");
            return json(["code"=>0,"msg"=>"","data"=>$giftSends,"count"=>$count]);
        }
        return self::bulidFail("未查询到相关数据");
    }
}