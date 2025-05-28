<?php
/**
 * Created by PhpStorm.
 * User: yun7_mac
 * Date: 2020-02-01
 * Time: 14:33
 */

namespace app\common\model;


use think\Model;

class LiveModel extends Model
{
    public function anchor(){
        return $this->hasOne('UserModel','id','anchorid')->with('profile')->field("id, nick_name, user_level, avatar, is_anchor, vip_date, vip_level, anchor_level");
    }

    public function category(){
        return $this->hasOne('LiveCategoryModel','id','categoryid');
    }
}