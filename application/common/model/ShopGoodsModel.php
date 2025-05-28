<?php


namespace app\common\model;


use think\Model;

class ShopGoodsModel extends Model
{
    public function shop(){
        return $this->hasOne(ShopModel::class,'id','shopid')->with('user');
    }

    public function category(){
        return $this->hasOne(ShopGoodsCategoryModel::class,'id','categoryid');
    }
}