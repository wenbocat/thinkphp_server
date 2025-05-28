<?php
/**
 * Created by PhpStorm.
 * User: yun7_mac
 * Date: 2019-12-20
 * Time: 11:05
 */

namespace app\common\model;


use think\Model;

class UserProfitModel extends Model
{
    public function user(){
        return $this->hasOne(UserModel::class,'id','uid')->with('profile')->field("id, nick_name, user_level");
    }

    public function moment(){
        return $this->hasOne(MomentModel::class, 'id','resid')->field('id,title');
    }

    public function gift(){
        return $this->hasOne(GiftModel::class, 'id','resid')->field("id, title, icon");
    }
}