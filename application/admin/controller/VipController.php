<?php
/**
 * Created by PhpStorm.
 * User: yun7_mac
 * Date: 2019-11-26
 * Time: 10:42
 */

namespace app\admin\controller;

use app\common\model\VipPriceModel;
use think\facade\Request;

class VipController extends BaseController
{
    public function index(){
        return $this->fetch();
    }

    public function add(){
        return $this->fetch();
    }

    public function edit(){
        $level = Request::param('level');
        $vipinfo = VipPriceModel::where(['level'=>$level])->find();
        $this->assign("vipinfo",$vipinfo);
        return $this->fetch();
    }

    public function getlist(){
        $vips = VipPriceModel::order("price","asc")->all();
        if (count($vips) > 0){
            return self::bulidSuccess($vips);
        }else{
            return self::bulidFail("未查询到相关数据");
        }
    }

    public function add_post(){
        $VipPriceModel = new VipPriceModel(Request::param());
        if ($VipPriceModel->save()){
            return self::bulidSuccess([]);
        }else{
            return self::bulidFail();
        }
    }

    public function edit_post(){

        $VipPriceModel = VipPriceModel::where(['level'=>Request::param('level')])->find();
        if (!$VipPriceModel){
            return self::bulidFail("条目不存在");
        }

        if ($VipPriceModel->save(Request::param())){
            return self::bulidSuccess([]);
        }else{
            return self::bulidFail();
        }
    }

    public function del_post(){
        if (VipPriceModel::destroy(Request::param('id'))){
            return self::bulidSuccess([]);
        }else{
            return self::bulidFail();
        }
    }
}