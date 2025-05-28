<?php


namespace app\common\model;


use think\Model;

class ShortvideoCollectModel extends Model
{
    public function video(){
        return $this->hasOne(ShortvideoModel::class,'id','videoid')->with('author');
    }
}