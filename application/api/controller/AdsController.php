<?php
/**
 * Created by PhpStorm.
 * User: yun7_mac
 * Date: 2019-12-13
 * Time: 15:55
 */

namespace app\api\controller;


use app\common\model\AdsModel;

class AdsController extends BaseController
{
    protected $NeedLogin = [];

    protected $rules = array(

    );

    public function initialize()
    {
        parent::initialize();
    }

    public function getLaunchAd(){
        $ad = AdsModel::where(["status"=>1, "type"=>1])->order("create_time asc")->find();
        return self::bulidSuccess($ad);
    }

    public function getHomeScrollAd(){
        $ad = AdsModel::where(["status"=>1, "type"=>2])->order("create_time asc")->select();
        return self::bulidSuccess($ad);
    }

    public function getHomePopAd(){
        $ad = AdsModel::where(["status"=>1, "type"=>3])->order("create_time asc")->select();
        return self::bulidSuccess($ad);
    }
}