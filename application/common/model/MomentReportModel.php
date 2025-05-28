<?php
/**
 * Created by PhpStorm.
 * User: yun7_mac
 * Date: 2020-01-07
 * Time: 16:17
 */

namespace app\common\model;


use think\Model;

class MomentReportModel extends Model
{
    public function user(){
        return $this->hasOne('UserModel','id','uid')->field('id, nick_name, avatar');
    }

    public function moment(){
        return $this->hasOne(MomentModel::class,'id','momentid')->field('id, title');
    }
}