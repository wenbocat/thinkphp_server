<?php
/**
 * Created by PhpStorm.
 * User: yun7_mac
 * Date: 2020-01-11
 * Time: 11:17
 */

namespace app\common\model;


use think\Model;

class MomentLikeModel extends Model
{
    public function moment(){
        return $this->hasOne(MomentModel::class,'id','momentid')->with('user');
    }
}