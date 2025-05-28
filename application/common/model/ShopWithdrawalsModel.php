<?php


namespace app\common\model;


use think\Model;

class ShopWithdrawalsModel extends Model
{
    public function shop(){
        return $this->hasOne(ShopModel::class,'id','shopid')->with('user');
    }
}