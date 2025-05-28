<?php


namespace app\admin\controller;


use app\common\model\AdminAuthModel;
use app\common\model\AdminRoleAuthModel;
use app\common\model\AdminRoleModel;
use think\Db;
use think\facade\Request;

class RoleController extends BaseController
{
    public function index(){
        $pid = Request::param('pid') ?? 0;
        $this->assign('pid',$pid);
        return $this->fetch();
    }

    public function getAdminAuthList(){
        $pid = Request::param('pid') ?? 0;
        $auths = AdminAuthModel::where(['parentid'=>$pid])->order(['sort'=>'asc','id'=>'asc'])->select();
        return self::bulidSuccess($auths);
    }

    public function getRoleList(){
        $list = AdminRoleModel::select();
        return self::bulidSuccess($list);
    }

    public function role(){
        return $this->fetch();
    }

    public function add_role(){
        $auths = AdminAuthModel::where(['status'=>1])->order(['parentid'=>'asc','sort'=>'asc'])->select();
        $tree_data = [];
        foreach ($auths as $auth){
            if ($auth->parentid == 0){
                $children = [];
                foreach ($auths as $auth_branch){
                    if ($auth->id == $auth_branch->parentid){
                        $tree_branch = ['title'=>$auth_branch->title,'id'=>$auth_branch->id];
                        $children[] = $tree_branch;
                    }
                }
                $tree_root = ['title'=>$auth->title,'id'=>$auth->id,'children'=>$children];
                $tree_data[] = $tree_root;
            }
        }
        $this->assign('data',json_encode($tree_data));
        return $this->fetch();
    }

    public function edit_role(){
        $role = AdminRoleModel::where(['id'=>Request::param('id')])->find();
        $this->assign('role',$role);

        $auths = AdminAuthModel::where(['status'=>1])->order(['parentid'=>'asc','sort'=>'asc'])->select();
        $role_auths = AdminRoleAuthModel::where(['roleid'=>$role->id])->select();
        $role_authids = array_column($role_auths->toArray(),'authid');
        $tree_data = [];
        foreach ($auths as $auth){
            if ($auth->parentid == 0){
                $children = [];
                foreach ($auths as $auth_branch){
                    if ($auth->id == $auth_branch->parentid){
                        $tree_branch = ['title'=>$auth_branch->title,'id'=>$auth_branch->id,'checked'=>false];
                        if (in_array($auth_branch->id,$role_authids)){
                            $tree_branch['checked'] = true;
                        }
                        $children[] = $tree_branch;
                    }
                }
                $tree_root = ['title'=>$auth->title,'id'=>$auth->id,'children'=>$children];
                $tree_data[] = $tree_root;
            }
        }
        $this->assign('data',json_encode($tree_data));
        return $this->fetch();
    }

    public function add_role_post(){
        $role = new AdminRoleModel();
        $role->name = Request::param('name');
        $role->remark = Request::param('remark');
        $role->is_visitor = Request::param('is_visitor');

        Db::startTrans();
        if ($role->save()){
            $auths = Request::param('authids');
            $role_auths = [];
            foreach ($auths as $authid){
                $role_auths[] = ['roleid'=>$role->id,'authid'=>$authid];
            }
            $roleAuthModel = new AdminRoleAuthModel();
            if ($roleAuthModel->saveAll($role_auths)){
                Db::commit();
                return self::bulidSuccess();
            }else{
                Db::rollback();
                return  self::bulidFail();
            }
        }
        Db::rollback();
        return  self::bulidFail();
    }

    public function edit_role_post(){
        $role = AdminRoleModel::where(['id'=>Request::param('id')])->find();
        if (!$role){
            return self::bulidFail('角色状态异常，请刷新后重试');
        }
        $role->name = Request::param('name');
        $role->remark = Request::param('remark');
        $role->is_visitor = Request::param('is_visitor');

        $role->save();

        if (AdminRoleAuthModel::where(['roleid'=>$role->id])->delete()){
            $auths = Request::param('authids');
            $role_auths = [];
            foreach ($auths as $authid){
                $role_auths[] = ['roleid'=>$role->id,'authid'=>$authid];
            }
            $roleAuthModel = new AdminRoleAuthModel();
            if ($roleAuthModel->saveAll($role_auths)){
                return self::bulidSuccess();
            }else{
                return self::bulidFail();
            }
        }
        return self::bulidFail();
    }


    public function add_auth(){
        $pid = Request::param('pid') ?? 0;
        $this->assign('pid',$pid);

        $roots = AdminAuthModel::where(['parentid'=>0])->select();
        $this->assign('roots',$roots);

        return $this->fetch();
    }

    public function edit_auth(){
        $id = Request::param('id');
        $auth = AdminAuthModel::where(['id'=>Request::param('id')])->find();
        $this->assign('auth',$auth);

        $roots = AdminAuthModel::where(['parentid'=>0])->select();
        $this->assign('roots',$roots);

        return $this->fetch();
    }

    public function auth_sort(){
        $auths = Request::param('sorts');
        foreach ($auths as $auth){
            AdminAuthModel::update(['sort'=>$auth['sort']],['id'=>$auth['id']]);
        }
        return self::bulidSuccess();
    }

    public function check_auth_post(){
        $id = Request::param('id');
        $auth = AdminAuthModel::where(['id'=>$id])->find();
        $auth->status == 1 ? $auth->status = 0 : $auth->status = 1;
        if ($auth->save()){
            return self::bulidSuccess($auth);
        }
        return self::bulidFail();
    }

    public function auth_add_post(){
        $sort = Request::param('sort');
        $pid = Request::param('parentid');
        if ($sort == -1){
            $maxsort = AdminAuthModel::where(['parentid'=>$pid])->order('sort','desc')->find()->sort;
            $sort = $maxsort + 1;
        }
        $auth = new AdminAuthModel(Request::param());
        $auth->sort = $sort;
        if ($auth->save()){
            return self::bulidSuccess();
        }
        return self::bulidFail();
    }

    public function auth_edit_post(){
        $auth = AdminAuthModel::where(['id'=>Request::param('id')])->find();
        $sort = Request::param('sort');
        $pid = Request::param('parentid');
        if ($sort == -1){
            $maxsort = AdminAuthModel::where(['parentid'=>$pid])->order('sort','desc')->find()->sort;
            $sort = $maxsort + 1;
        }
        $auth->sort = $sort;
        $auth->parentid = $pid;
        $auth->path = Request::param('path');
        $auth->icon = Request::param('icon');
        $auth->title = Request::param('title');
        if ($auth->save()){
            return self::bulidSuccess();
        }
        return self::bulidFail();
    }
}