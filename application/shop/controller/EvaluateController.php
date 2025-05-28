<?php


namespace app\shop\controller;


use app\common\model\ShopGoodsEvaluateModel;
use app\common\model\ShopOrderGoodsModel;
use think\Db;
use think\facade\Request;

class EvaluateController extends BaseController
{
    /**
     * 需要登录的方法
     */
    protected $NeedLogin = ['submit'];

    /**
     * 参数校验规则
     */
    protected $rules = [
        'submit'=>[
            'ordergoodsid'=>'require',
            'goodsid'=>'require',
            'content'=>'require',
            'score'=>'require'
        ],
        'goodsevaluatelist'=>[
            'goodsid'=>'require',
        ]
    ];

    public function initialize()
    {
        parent::initialize();
    }

    public function submit(){
        $ordergoods = ShopOrderGoodsModel::where(['id'=>Request::param('ordergoodsid'),'uid'=>$this->userinfo->id])->find();
        if (!$ordergoods){
            return self::bulidFail('订单信息有误');
        }
        if ($ordergoods->evaluate_status != 0){
            return self::bulidFail('您已评价过该商品');
        }
        $evaluate = new ShopGoodsEvaluateModel(Request::param());
        $evaluate->create_time = nowFormat();
        $ordergoods->evaluate_status = 1;
        Db::startTrans();
        if ($evaluate->save() && $ordergoods->save()){
            Db::commit();
            return self::bulidSuccess();
        }
        Db::rollback();
        return self::bulidFail();
    }

    public function goodsEvaluateList(){
        $page = Request::param('page') ?? 1;
        $size = Request::param('size') ?? 20;
        $type = Request::param('type') ?? 0;

        $total_count = ShopGoodsEvaluateModel::where(['goodsid'=>Request::param('goodsid')])->count('id');
        $good_count = ShopGoodsEvaluateModel::where(['goodsid'=>Request::param('goodsid')])->where('score','>','2')->count('id');
        $img_count = ShopGoodsEvaluateModel::where(['goodsid'=>Request::param('goodsid')])->where('img_urls','<>','')->count('id');

        if ($type == 1){
            $list = ShopGoodsEvaluateModel::where(['goodsid'=>Request::param('goodsid')])->where('score','>','2')->with('ordergoods,user')->order(['create_time'=>'desc'])->limit(($page-1)*$size,$size)->select();
        }elseif ($type == 2){
            $list = ShopGoodsEvaluateModel::where(['goodsid'=>Request::param('goodsid')])->where('img_urls','<>','')->with('ordergoods,user')->order(['create_time'=>'desc'])->limit(($page-1)*$size,$size)->select();
        }else{
            $list = ShopGoodsEvaluateModel::where(['goodsid'=>Request::param('goodsid')])->with('ordergoods,user')->order(['create_time'=>'desc'])->limit(($page-1)*$size,$size)->select();
        }
        return self::bulidSuccess(['list'=>$list,'total_count'=>$total_count,'good_count'=>$good_count,'img_count'=>$img_count]);
    }
}