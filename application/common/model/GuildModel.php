<?php
/**
 * Created by PhpStorm.
 * User: yun7_mac
 * Date: 2019-11-30
 * Time: 21:40
 * Info: 公会模型
 */

namespace app\common\model;


use think\Model;

class GuildModel extends Model
{
    public function users(){
        return $this->hasMany('UserModel','guildid','id')->with('profile')->field("id, nick_name, user_level, anchor_level, avatar, guildid");
    }
}