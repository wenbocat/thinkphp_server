<?php


namespace app\common\model;


use think\Model;

class ShopGoodsEvaluateModel extends Model
{
    public function ordergoods(){
        return $this->hasOne(ShopOrderGoodsModel::class,'id','ordergoodsid');
    }
    public function user(){
        return $this->hasOne(UserModel::class,'id','uid')->field('id,nick_name,avatar');
    }
}