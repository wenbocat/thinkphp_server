<?php
/**
 * Created by PhpStorm.
 * User: yun7_mac
 * Date: 2019-11-06
 * Time: 17:40
 */

namespace app\common\model;

use think\Model;

class UserModel extends Model
{
    public function profile(){
        return $this->hasOne(UserProfileModel::class,'uid','id');
    }

    public function live(){
        return $this->hasOne(LiveModel::class,'anchorid','id')->with('anchor');
    }
}