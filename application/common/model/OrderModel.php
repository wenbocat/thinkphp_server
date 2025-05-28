<?php
/**
 * Created by PhpStorm.
 * User: yun7_mac
 * Date: 2019-11-27
 * Time: 10:50
 */

namespace app\common\model;


use think\Model;

class OrderModel extends Model
{
    public function user(){
        return $this->hasOne('UserModel','id','uid')->with('profile')->field("id, nick_name, avatar, user_level");
    }
}