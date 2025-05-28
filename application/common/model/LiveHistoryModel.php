<?php


namespace app\common\model;


use think\Model;

class LiveHistoryModel extends Model
{
    public function anchor(){
        return $this->hasOne('UserModel','id','anchorid')->with('profile')->field("id, nick_name, user_level, avatar");
    }

    public function category(){
        return $this->hasOne('LiveCategoryModel','id','categoryid');
    }
}