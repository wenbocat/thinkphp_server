<?php
/**
 * Created by PhpStorm.
 * User: yun7_mac
 * Date: 2020-01-02
 * Time: 14:31
 */

namespace app\common\model;


use think\Model;

class MomentModel extends Model
{
    public function user(){
        return $this->hasOne(UserModel::class,'id','uid')->with('profile')->field("id, nick_name, is_anchor, vip_date, vip_level, avatar, user_level, anchor_level");
    }
}