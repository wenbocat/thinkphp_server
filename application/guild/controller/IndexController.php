<?php
/**
 * Created by PhpStorm.
 * User: yun7_mac
 * Date: 2019-11-05
 * Time: 15:55
 */

namespace app\guild\controller;


use app\common\model\AdminAuthModel;
use app\common\model\AdminRoleAuthModel;
use app\common\model\StatisticsModel;
use app\common\model\UserModel;
use think\Db;
use think\facade\Request;

class IndexController extends BaseController
{
    public function index(){
        $this->assign('config_pub',getConfigPub());
        $this->assign("userinfo",$this->userinfo);

        return $this->fetch();
    }

    public function home(){
        $this->assign('guild',$this->userinfo);
        return $this->fetch();
    }

    public function edit(){
        $this->assign('guild',$this->userinfo);
        return $this->fetch();
    }

    public function edit_post(){
        if ($this->userinfo->save(Request::param())){
            return self::bulidSuccess();
        }
        return self::bulidFail();
    }
    
    public function noOperation(){
        return json(["code"=>0,"msg"=>""]);
    }
}