<?php
/**
 * Created by PhpStorm.
 * User: yun7_mac
 * Date: 2019-12-09
 * Time: 09:39
 */

namespace app\common\model;


use think\Model;

class UserRecModel extends Model
{
    public function user(){
        return $this->hasOne('UserModel','id','uid')->field("id, nick_name, age, gender, user_level, anchor_level");;
    }
}