<?php
/**
 * Created by PhpStorm.
 * User: yun7_mac
 * Date: 2019-12-31
 * Time: 18:42
 */

namespace app\common\model;


use think\Model;

class IntimacyModel extends Model
{
    public function anchor(){
        return $this->hasOne(UserModel::class,'id','anchorid')->with('profile')->field("id, nick_name, user_level, avatar, is_anchor, vip_date, vip_level, anchor_level");
    }

    public function user(){
        return $this->hasOne('UserModel','id','uid')->with('profile')->field("id, nick_name, user_level, avatar, is_anchor, vip_date, vip_level, anchor_level");
    }
}