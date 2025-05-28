<?php
/**
 * Created by PhpStorm.
 * User: yun7_mac
 * Date: 2019-11-26
 * Time: 10:42
 */

namespace app\admin\controller;


use app\common\model\GoldPriceModel;
use think\facade\Request;

class GoldController extends BaseController
{
    public function index(){
        return $this->fetch();
    }

    public function add(){
        return $this->fetch();
    }

    public function edit(){
        $id = Request::param('id');
        $goldinfo = GoldPriceModel::get($id);
        $this->assign("goldinfo",$goldinfo);
        return $this->fetch();
    }

    public function getlist(){
        $golds = GoldPriceModel::order("price","asc")->all();
        if (count($golds) > 0){
            return self::bulidSuccess($golds);
        }else{
            return self::bulidFail("未查询到相关数据");
        }
    }

    public function add_post(){
        $goldPriceModel = new GoldPriceModel(Request::param());
        if ($goldPriceModel->save()){
            return self::bulidSuccess([]);
        }else{
            return self::bulidFail();
        }
    }

    public function edit_post(){

        $goldPriceModel = GoldPriceModel::get(Request::param('id'));
        if (!$goldPriceModel){
            return self::bulidFail("条目不存在");
        }

        if ($goldPriceModel->save(Request::param())){
            return self::bulidSuccess([]);
        }else{
            return self::bulidFail();
        }
    }

    public function del_post(){
        if (GoldPriceModel::destroy(Request::param('id'))){
            return self::bulidSuccess([]);
        }else{
            return self::bulidFail();
        }
    }
}