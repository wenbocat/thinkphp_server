<?php
/**
 * Created by PhpStorm.
 * User: yun7_mac
 * Date: 2019-12-02
 * Time: 10:39
 */

namespace app\common\model;


use think\Model;

class AnchorFansModel extends Model
{
    public function anchor(){
        return $this->hasOne(UserModel::class,'id','anchorid')->with(['profile','live'])->field("id, nick_name, is_anchor, vip_date, vip_level, avatar, user_level, anchor_level");
    }
    public function fan(){
        return $this->hasOne(UserModel::class,'id','fansid')->with('profile')->field("id, nick_name, is_anchor, vip_date, vip_level, avatar, user_level, anchor_level");
    }
}