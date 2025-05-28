<?php


namespace app\common\model;


use think\Model;

class GuildMemberApplyModel extends Model
{
    public function user(){
        return $this->hasOne(UserModel::class,'id','uid')->with(['profile'])->field('id,account,avatar,nick_name,anchor_level');
    }
}