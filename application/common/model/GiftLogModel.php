<?php
/**
 * Created by PhpStorm.
 * User: yun7_mac
 * Date: 2019-11-29
 * Time: 11:29
 */

namespace app\common\model;


use think\Model;

class GiftLogModel extends Model
{
    public function gift(){
        return $this->hasOne('GiftModel','id','giftid')->field("id, title, icon");
    }

    public function anchor(){
        return $this->hasOne('UserModel','id','anchorid')->with('profile')->field("id, nick_name, user_level, avatar, is_anchor, vip_date, vip_level, anchor_level");
    }

    public function user(){
        return $this->hasOne('UserModel','id','uid')->with('profile')->field("id, nick_name, user_level, avatar, is_anchor, vip_date, vip_level, anchor_level");
    }
}