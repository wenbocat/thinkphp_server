<?php


namespace app\common\model;


use think\Model;

class AnchorReportModel extends Model
{
    public function anchor(){
        return $this->hasOne('UserModel','id','anchorid')->field('id, nick_name, avatar');
    }

    public function user(){
        return $this->hasOne('UserModel','id','uid')->field('id, nick_name, avatar');
    }
}