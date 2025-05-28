<?php
/**
 * Created by PhpStorm.
 * User: yun7_mac
 * Date: 2020-02-06
 * Time: 11:13
 */

namespace app\common\model;


use think\Model;

class UserAuthModel extends Model
{
    public function user(){
        return $this->hasOne('UserModel','id','uid')->with('profile')->field("id, nick_name, user_level");
    }
}