<?php


namespace app\common\model;


use think\Model;

class AdminRoleAuthModel extends Model
{
    public function auth(){
        return $this->hasOne(AdminAuthModel::class,'id','authid');
    }
}