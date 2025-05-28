<?php


namespace app\common\model;


use think\Model;

class ShopOrderGoodsModel extends Model
{
    public function goods(){
        return $this->hasOne(ShopGoodsModel::class,'id','goodsid');
    }
    public function suborder(){
        return $this->hasOne(ShopSuborderModel::class,'id','suborderid');
    }
    public function shop(){
        return $this->hasOne(UserModel::class,'id','shopid')->field('id,avatar,nick_name');
    }
    public function user(){
        return $this->hasOne(UserModel::class,'id','uid')->field('id,avatar,nick_name');
    }
}