<?php
/**
 * Created by PhpStorm.
 * User: yun7_mac
 * Date: 2020-01-02
 * Time: 14:31
 */

namespace app\common\model;


use think\Model;

class ShortvideoModel extends Model
{
    public function author(){
        return $this->hasOne(UserModel::class,'id','uid')->with('profile')->field("id, nick_name, avatar, user_level");
    }
}