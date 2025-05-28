<?php


namespace app\common\model;


use think\Model;

class ShopGoodsVisitsModel extends Model
{
    public function goods(){
        return $this->hasOne(ShopGoodsModel::class,'id','goodsid')->with('shop');
    }
}