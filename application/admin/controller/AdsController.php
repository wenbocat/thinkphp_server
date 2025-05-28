<?php
/**
 * Created by PhpStorm.
 * User: yun7_mac
 * Date: 2019-12-09
 * Time: 15:52
 */

namespace app\admin\controller;


use app\common\model\AdsModel;
use think\facade\Request;

class AdsController extends BaseController
{
    public function appindex(){
        return $this->fetch();
    }

    public function appadd(){
        return $this->fetch();
    }

    public function appedit(){
        $ads = AdsModel::get(Request::param('id'));
        $this->assign("adsInfo",$ads);
        return $this->fetch();
    }

    public function signForCos()
    {
        return parent::signForCos();
    }

    public function getlist(){
        $type = Request::param("type");
        $ads = AdsModel::where(["type"=>$type])->order("create_time desc")->select();
        if (count($ads))
            return self::bulidSuccess($ads);
        return self::bulidFail("未查询到相关数据");
    }

    public function opt_post(){
        $id = Request::param('id');

        $ads = AdsModel::get($id);

        if (!$ads){
            return self::bulidFail("广告条目不存在");
        }

        $status = $ads->status == 1?0:1;
        if ($ads->save(["status"=>$status])){
            return self::bulidSuccess([]);
        }
        return self::bulidFail();
    }

    public function appadd_post(){
        $ads = new AdsModel(Request::param());
        $ads->create_time = date("Y-m-d H:i:s");
        if ($ads->save()){
            return self::bulidSuccess([]);
        }
        return self::bulidFail();
    }

    public function appedit_post(){
        $ads = AdsModel::get(Request::param('id'));
        if (!$ads){
            return self::bulidFail("广告条目不存在");
        }
        if ($ads->save(Request::param())){
            return self::bulidSuccess([]);
        }
        return self::bulidFail();
    }

    public function appdel_post(){
        if (AdsModel::destroy(Request::param('id'))){
            return self::bulidSuccess([]);
        }else{
            return self::bulidFail();
        }
    }
}