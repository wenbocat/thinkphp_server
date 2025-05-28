<?php


namespace app\admin\controller;


use app\common\model\AdminModel;
use app\common\model\AdminRoleModel;
use think\facade\Request;

class AdminController extends BaseController
{
    public function index(){
        return $this->fetch();
    }

    public function add(){
        $role = AdminRoleModel::where(['status'=>1])->select();
        $this->assign('role',$role);
        return $this->fetch();
    }

    public function edit(){
        $role = AdminRoleModel::where(['status'=>1])->select();
        $this->assign('role',$role);
        $admin = AdminModel::where(['id'=>Request::param('id')])->find();
        $this->assign('admin',$admin);
        return $this->fetch();
    }

    public function getList(){
        $admin = AdminModel::with('role')->field('password,token',true)->select();
        return self::bulidSuccess($admin);
    }

    public function add_post(){
        $admin = new AdminModel(Request::param());
        $admin->password = openssl_encrypt($admin->account."123456","DES-ECB",$this->OPENSSL_KEY);
        $admin->create_time = nowFormat();
        if ($admin->save()){
            return self::bulidSuccess([],'添加成功，默认密码123456');
        }
        return self::bulidFail();
    }

    public function edit_post(){
        $admin = AdminModel::where(['id'=>Request::param('id')])->find();
        if (!$admin){
            return self::bulidFail('用户状态异常');
        }
        $admin->name = Request::param('name');
        $admin->roleid = Request::param('roleid');
        if ($admin->save()){
            return self::bulidSuccess();
        }
        return self::bulidFail();
    }

    public function del_post(){
        if (AdminModel::where(['id'=>Request::param('id')])->delete()){
            return self::bulidSuccess();
        }
        return self::bulidFail();
    }
}