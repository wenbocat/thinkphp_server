<?php


namespace app\common\model;


use think\Model;

class ShopOrderReturnModel extends Model
{
    public function goods(){
        return $this->hasOne(ShopOrderGoodsModel::class,'id','ordergoodsid')->with('goods');
    }
    public function suborder(){
        return $this->hasOne(ShopSuborderModel::class,'id','suborderid');
    }
    public function shop(){
        return $this->hasOne(UserModel::class,'id','shopid')->field('id,avatar,nick_name');
    }
}