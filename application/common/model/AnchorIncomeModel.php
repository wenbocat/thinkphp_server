<?php


namespace app\common\model;


use think\Model;

class AnchorIncomeModel extends Model
{
    public function anchor(){
        return $this->hasOne(UserModel::class,'id','anchorid')->with('profile')->field("id, nick_name, user_level, avatar, is_anchor, vip_date, vip_level, anchor_level");
    }
}