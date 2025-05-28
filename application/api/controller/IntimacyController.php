<?php
/**
 * Created by PhpStorm.
 * User: yun7_mac
 * Date: 2020-01-08
 * Time: 09:35
 */

namespace app\api\controller;


use app\common\model\IntimacyModel;
use think\facade\Request;

class IntimacyController extends BaseController
{
    protected $NeedLogin = [];

    protected $rules = array(
        'gettotalintimacyrank'=>[
            'anchorid'=>'require'
        ],
        'getweekintimacyrank'=>[
            'anchorid'=>'require'
        ],
    );

    public function getTotalIntimacyRank(){
        $anchorid = Request::param('anchorid');
        $page = Request::post("page");
        $size = Request::post("size");
        if (!$page){
            $page = 1;
        }
        if (!$size){
            $size = 10;
        }
        $intimacys = IntimacyModel::where('anchorid', $anchorid)->with(['user'])->order('intimacy desc')->limit(($page-1)*$size,$size)->select();
        return self::bulidSuccess($intimacys);
    }

    public function getWeekIntimacyRank(){
        $anchorid = Request::param('anchorid');
        $page = Request::post("page");
        $size = Request::post("size");
        if (!$page){
            $page = 1;
        }
        if (!$size){
            $size = 10;
        }
        $intimacys = IntimacyModel::where(['anchorid'=>$anchorid])->where('intimacy_week','>','0')->with(['user'])->order('intimacy_week desc')->limit(($page-1)*$size,$size)->select();
        return self::bulidSuccess($intimacys);
    }
}