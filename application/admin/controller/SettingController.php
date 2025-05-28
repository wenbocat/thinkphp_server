<?php
/**
 * Created by PhpStorm.
 * User: yun7_mac
 * Date: 2019-11-22
 * Time: 11:39
 */

namespace app\admin\controller;


use app\common\model\AdminAuthModel;
use app\common\model\AdminRoleAuthModel;
use app\common\model\AdminRoleModel;
use app\common\model\ConfigPriModel;
use app\common\model\ConfigPubModel;
use app\common\model\ConfigTagModel;
use think\Db;
use think\facade\Request;

class SettingController extends BaseController
{
    public function indexpri(){
        $config_pri = ConfigPriModel::get(1);
        $this->assign("config_pri",$config_pri);
        return $this->fetch();
    }

    public function indexpb(){
        $config_pb = ConfigPubModel::get(1);
        $this->assign("config_pub",$config_pb);
        return $this->fetch();
    }

    public function tag(){
        return $this->fetch();
    }

    public function add_tag(){
        return $this->fetch();
    }

    public function edit_tag(){
        $tagModel = ConfigTagModel::get(Request::param("id"));
        $this->assign("taginfo",$tagModel);
        return $this->fetch();
    }

    public function savepri(){
        $param = Request::param();
        $config_pri = ConfigPriModel::get(1);
        if ($config_pri->save($param)){
            redis_set('ConfigPri',$config_pri);
            return self::bulidSuccess([$config_pri]);
        }
        return self::bulidFail();
    }

    public function savepub(){
        $param = Request::param();
        $config_pub = ConfigPubModel::get(1);
        if ($config_pub->save($param)){
            redis_set('ConfigPub',$config_pub);
            return self::bulidSuccess([$config_pub]);
        }
        return self::bulidDataFail($config_pub);
    }

    public function gettags(){
        return self::bulidSuccess(ConfigTagModel::all());
    }

    public function add_tag_post(){
        $tagModel = new ConfigTagModel(Request::param());
        if ($tagModel->save()){
            return self::bulidSuccess([]);
        }
        return self::bulidFail();
    }

    public function edit_tag_post(){
        $tagModel = ConfigTagModel::get(Request::param("id"));
        if ($tagModel->save(Request::param())){
            return self::bulidSuccess([]);
        }
        return self::bulidFail();
    }
}