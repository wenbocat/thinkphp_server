<?php
/**
 * Created by PhpStorm.
 * User: yun7_mac
 * Date: 2020-01-11
 * Time: 15:06
 */

namespace app\common\model;


use think\Model;

class MomentCollectModel extends Model
{
    public function moment(){
        return $this->hasOne(MomentModel::class,'id','momentid')->with('user');
    }
}