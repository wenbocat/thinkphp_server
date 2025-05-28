<?php


namespace app\common\model;


use think\Model;

class AgentModel extends Model
{
    public function user(){
        return $this->hasOne(UserModel::class,'id','uid')->field("id, account, nick_name");
    }
}