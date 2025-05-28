<?php


namespace app\common\model;


use think\Model;

class ShopCartGoodsModel extends Model
{
    public function goods(){
        return $this->hasOne(ShopGoodsModel::class,'id','goodsid');
    }

    public function inventory(){
        return $this->hasOne(ShopGoodsInventoryModel::class,'id','inventoryid')->with(['color','size']);
    }
}