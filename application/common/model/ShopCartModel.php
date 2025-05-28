<?php


namespace app\common\model;


use think\Model;

class ShopCartModel extends Model
{
    public function shop(){
        return $this->hasOne(ShopModel::class,'id','shopid')->with('user');
    }

    public function cartgoods(){
        return $this->hasMany(ShopCartGoodsModel::class,'shopid','shopid')->with('goods,inventory');
    }
}