<?php
/**
 * Created by PhpStorm.
 * User: yun7_mac
 * Date: 2020-01-02
 * Time: 18:02
 */

namespace app\common\model;


use think\Model;

class ShortvideoCommentModel extends Model
{
    public function user(){
        return $this->hasOne(UserModel::class,'id','uid')->with('profile')->field("id, avatar, nick_name, user_level");
    }

    public function touser(){
        return $this->hasOne(UserModel::class,'id','touid')->with('profile')->field("id, avatar, nick_name, user_level");
    }
}