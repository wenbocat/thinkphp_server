<?php
/**
 * Created by PhpStorm.
 * User: yun7_mac
 * Date: 2019-11-19
 * Time: 13:39
 */

namespace app\common\model;


use think\Model;

class AdminModel extends Model
{
    public function role(){
        return $this->hasOne(AdminRoleModel::class,'id','roleid');
    }
}