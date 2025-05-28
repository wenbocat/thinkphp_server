<?php


namespace app\common\model;


use think\Model;

class LiveRoomManagerModel extends Model
{
    public function user(){
        return $this->hasOne('UserModel','id','mgrid')->with('profile')->field("id, nick_name, user_level, avatar, is_anchor, vip_date, vip_level, anchor_level");
    }

    public function anchor(){
        return $this->hasOne('UserModel','id','anchorid')->with('profile')->field("id, nick_name, user_level, avatar, is_anchor, vip_date, vip_level, anchor_level");
    }
}