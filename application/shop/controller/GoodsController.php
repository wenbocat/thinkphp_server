<?php


namespace app\shop\controller;


use app\common\model\ShopGoodsCategoryModel;
use app\common\model\ShopGoodsColorModel;
use app\common\model\ShopGoodsEvaluateModel;
use app\common\model\ShopGoodsInventoryModel;
use app\common\model\ShopGoodsModel;
use app\common\model\ShopGoodsSizeModel;
use app\common\model\ShopGoodsVisitsModel;
use app\common\model\ShopModel;
use think\Db;
use think\facade\Request;

class GoodsController extends BaseController
{
    /**
     * 需要登录的方法
     */
    protected $NeedLogin = ['publishGoods','editGoodsInventory'];

    /**
     * 参数校验规则
     */
    protected $rules = [
        'publishgoods'=>[
            'categoryid'=>'require',
            'title'=>'require',
            'thumb_urls'=>'require',
            'desc'=>'require',
            'desc_img_urls'=>'require',
            'delivery'=>'require',
            'freight'=>'require',
            'inventorys'=>'require',
        ],
        'getgoodslist'=>[
            'shopid'=>'require'
        ],
        'getgoodsinfo'=>[
            'goodsid'=>'require'
        ],
    ];

    public function initialize()
    {
        parent::initialize();
    }

    public function getCategoryList(){
        $list_root = ShopGoodsCategoryModel::where(['status'=>1,'parentid'=>0])->select();
        $list_sub = ShopGoodsCategoryModel::where(['status'=>1])->where('parentid','<>','0')->select();

        foreach ($list_root as $root){
            $subcates = [];
            foreach ($list_sub as $cate){
                if ($cate->parentid == $root->id){
                    $subcates[] = $cate;
                }
            }
            $root->subcates = $subcates;
        }
        return self::bulidSuccess($list_root);
    }

    public function publishGoods(){
        $shop = ShopModel::where(['id'=>$this->userinfo->id])->find();
        if (!$shop){
            return self::bulidSuccess('您尚未开通小店');
        }
        if ($shop->status != 1){
            return self::bulidSuccess('小店状态异常');
        }

        Db::startTrans();

        $goods = new ShopGoodsModel(Request::param());
        $goods->create_time = nowFormat();
        $goods->shopid = $shop->id;
        if (!$goods->save()){
            Db::rollback();
            return self::bulidFail();
        }

        $colorModels = [];
        if($colors = Request::param('colors')) {
            $data = [];
            foreach ($colors as $color){
                $color['goodsid'] = $goods->id;
                $data[] = $color;
            }
            $colorModel = new ShopGoodsColorModel();
            $colorModels = $colorModel->saveAll($data);
        }

        $sizeModels = [];
        if($sizes = Request::param('sizes')) {
            $data = [];
            foreach ($sizes as $size){
                $size['goodsid'] = $goods->id;
                $data[] = $size;
            }
            $sizeModel = new ShopGoodsSizeModel();
            $sizeModels = $sizeModel->saveAll($data);
        }

        if (count($colorModels) != count($colors) || count($sizeModels) != count($sizes)){
            Db::rollback();
            return self::bulidFail();
        }

        $inventorys = Request::param('inventorys');
        $inventoryData = [];
        $price = PHP_INT_MAX;
        foreach ($inventorys as $inventory){
            $price = $inventory['price'] < $price ? $inventory['price'] : $price;
            $inventory['goodsid'] = $goods->id;
            $inventory['colorid'] = 0;
            $inventory['sizeid'] = 0;

            if ($inventory['color']){
                foreach ($colorModels as $colorModel){
                    if ($inventory['color'] == $colorModel->color){
                        $inventory['colorid'] = $colorModel->id;
                    }
                }
            }

            if ($inventory['size']) {
                foreach ($sizeModels as $sizeModel) {
                    if ($inventory['size'] == $sizeModel->size) {
                        $inventory['sizeid'] = $sizeModel->id;
                    }
                }
            }
            unset($inventory['color']);
            unset($inventory['size']);
            $inventoryData[] = $inventory;
        }
        $goods->price = $price;
        $goods->save();
        $inventory_save = Db::table('db_shop_goods_inventory')->insertAll($inventoryData) == count($inventorys);
        if (!$inventory_save){
            Db::rollback();
            return self::bulidFail();
        }
        Db::commit();
        return self::bulidSuccess([],'发布成功，请等待管理员审核');
    }

    public function editGoodsInventory(){
        $goodsid = Request::param('goodsid');
        $inventorys = Request::param('inventorys');
        //查询是否是自己的商品
        $goods = ShopGoodsModel::where(['id'=>$goodsid,'shopid'=>$this->userinfo->id])->find();
        if (!$goods){
            return self::bulidFail();
        }
        $price = $goods->price;
        foreach ($inventorys as $inventory){
            $price = $inventory['price'] < $price ? $inventory['price'] : $price;
            ShopGoodsInventoryModel::where(['id'=>$inventory['id']])->update(['left_count'=>$inventory['left_count'],'price'=>$inventory['price']]);
        }
        if ($price != $goods->price){
            $goods->save();
        }
        return self::bulidSuccess();
    }

    public function getGoodsList(){
        $shopid = Request::param('shopid');
        $page = Request::param('page') ?? 1;
        $size = Request::param("size") ?? 20;
        $goods = ShopGoodsModel::where(['shopid'=>$shopid,'status'=>1])->limit(($page - 1)*$size,$size)->select();

        $count = ShopGoodsModel::where(['shopid'=>$shopid,'status'=>1])->count("*");

        $shop = ShopModel::where(['id'=>$shopid])->with('user')->find();
        return self::bulidSuccess(['list'=>$goods,'count'=>$count,'shop'=>$shop]);
    }

    public function getGoodsInfo(){
        $goodsid = Request::param('goodsid');
        $goods = ShopGoodsModel::where(['id'=>$goodsid,'status'=>1])->with(['shop','category'])->find();
        if (!$goods){
            return self::bulidFail('商品不存在或已下架');
        }
        $colors = ShopGoodsColorModel::where(['goodsid'=>$goodsid])->select();
        $sizes = ShopGoodsSizeModel::where(['goodsid'=>$goodsid])->select();
        $inventory = ShopGoodsInventoryModel::where(['goodsid'=>$goodsid])->select();

        //读取评价第一条
        $evaluate = ShopGoodsEvaluateModel::where(['goodsid'=>$goodsid])->with('user')->order(['create_time'=>'desc'])->find();
        $evaluate_count = ShopGoodsEvaluateModel::where(['goodsid'=>$goodsid])->count("id");

        if ($uid = Request::param('uid')){
            //写入足迹
            $visits = ShopGoodsVisitsModel::where(['uid'=>$uid,'goodsid'=>$goodsid])->find();
            if ($visits){
                $visits->visits_time = nowFormat();
            }else{
                $visits = new ShopGoodsVisitsModel(['uid'=>$uid,'goodsid'=>$goodsid,'visits_time'=>nowFormat()]);
            }
            $visits->save();
        }

        return self::bulidSuccess(['goods'=>$goods,'colors'=>$colors,'sizes'=>$sizes,'inventory'=>$inventory,'evaluate'=>$evaluate,'evaluate_count'=>$evaluate_count]);
    }
}