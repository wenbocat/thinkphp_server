<?php


namespace app\common\model;


use think\Model;

class UserConsumeModel extends Model
{
    public function user(){
        return $this->hasOne(UserModel::class,'id','uid')->with('profile')->field("id, nick_name, user_level, avatar, is_anchor, vip_date, vip_level, anchor_level");
    }
}