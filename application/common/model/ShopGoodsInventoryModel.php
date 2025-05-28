<?php


namespace app\common\model;


use think\Model;

class ShopGoodsInventoryModel extends Model
{
    public function color(){
        return $this->hasOne(ShopGoodsColorModel::class,'id','colorid');
    }
    public function size(){
        return $this->hasOne(ShopGoodsSizeModel::class,'id','sizeid');
    }
}