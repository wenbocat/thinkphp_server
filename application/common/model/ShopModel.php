<?php


namespace app\common\model;

use think\Model;

class ShopModel extends Model
{
    public function user(){
        return $this->hasOne(UserModel::class,'id','id')->field("id, account, nick_name, avatar");
    }
}