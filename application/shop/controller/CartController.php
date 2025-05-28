<?php


namespace app\shop\controller;


use app\common\model\ShopCartGoodsModel;
use app\common\model\ShopCartModel;
use app\common\model\ShopGoodsInventoryModel;
use app\common\model\ShopGoodsModel;
use think\facade\Request;

class CartController extends BaseController
{
    /**
     * 需要登录的方法
     */
    protected $NeedLogin = ['getCartGoodsList','addCartGoods','delCartGoods','editCartGoodsCount'];

    /**
     * 参数校验规则
     */
    protected $rules = [
        'addcartgoods'=>[
            'shopid'=>'require',
            'inventoryid'=>'require',
            'count'=>'require',
        ],
        'delcartgoods'=>[
            'cartgoodsids'=>'require',
        ],
        'editcartgoodscount'=>[
            'cartgoodsid'=>'require',
            'count'=>'require',
        ]
    ];

    public function getCartGoodsList(){
        $page = Request::param('page') ?? 1;
        $size = Request::param("size") ?? 20;

        $uid = $this->userinfo->id;
        $list = ShopCartModel::has('cartgoods','>',0)->where(['ShopCartModel.uid'=>$uid])->with(['shop','cartgoods'=>function($query) use ($uid){
            $query->where(['uid'=>$uid]);
        }])->order(['operate_time'=>'desc'])->limit(($page-1)*$size,$size)->select();
        return self::bulidSuccess($list);
    }

    public function addCartGoods(){
        $inventoryid = Request::param('inventoryid');
        $count = Request::param('count');
        $shopid = Request::param('shopid');
        $inventory = ShopGoodsInventoryModel::where(['id'=>$inventoryid])->find();
        if (!$inventory){
            return self::bulidFail('商品数据有误');
        }
        if ($inventory->left_count < $count){
            return self::bulidFail('商品库存不足');
        }
        $goods = ShopGoodsModel::where(['id'=>$inventory->goodsid,'status'=>1,'shopid'=>$shopid])->find();
        if (!$goods){
            return self::bulidFail('商品已下架');
        }
        $cartModel = ShopCartModel::where(['uid'=>$this->userinfo->id,'shopid'=>$shopid])->find();
        if (!$cartModel){
            $cartModel = new ShopCartModel(['uid'=>$this->userinfo->id,'shopid'=>$shopid]);
        }
        $cartModel->operate_time = nowFormat();

        $cartGoods = ShopCartGoodsModel::where(['uid'=>$this->userinfo->id,'inventoryid'=>$inventoryid])->find();
        if ($cartGoods){
            $cartGoods->count = ['inc',$count];
        }else{
            $cartGoods = new ShopCartGoodsModel(['uid'=>$this->userinfo->id,'inventoryid'=>$inventoryid,'count'=>$count,'shopid'=>$shopid,'goodsid'=>$goods->id]);
        }

        if ($cartGoods->save() && $cartModel->save()){
            return self::bulidSuccess();
        }
        return self::bulidFail();
    }

    public function delCartGoods(){
        $cartids = explode(',',Request::param('cartgoodsids'));
        if (ShopCartGoodsModel::where(['uid'=>$this->userinfo->id])->where('id','in',$cartids)->delete()){
            return self::bulidSuccess();
        }
        return self::bulidFail();
    }

    public function editCartGoodsCount(){
        $cartgoodsid = Request::param('cartgoodsid');
        $cartGoods = ShopCartGoodsModel::where(['uid'=>$this->userinfo->id,'id'=>$cartgoodsid])->find();
        if (!$cartGoods){
            return self::bulidFail('数据错误');
        }
        $cartGoods->count = Request::param('count');
        if ($cartGoods->save()){
            ShopCartModel::update(['operate_time'=>nowFormat()],['uid'=>$this->userinfo->id,'shopid'=>$cartGoods->shopid]);
            return self::bulidSuccess();
        }
        return self::bulidFail();
    }

    public static function autoDelCartGoods($inventoryids,$uid){
        ShopCartGoodsModel::where(['uid'=>$uid])->where('inventoryid','in',$inventoryids)->delete();
    }

}