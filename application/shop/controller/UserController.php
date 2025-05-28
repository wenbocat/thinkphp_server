<?php


namespace app\shop\controller;


use app\common\model\ShopGoodsVisitsModel;
use app\common\model\ShopModel;
use app\common\model\ShopOrderGoodsModel;
use app\common\model\ShopOrderReturnModel;
use app\common\model\ShopSuborderModel;
use app\common\model\UserAddressModel;
use think\Db;
use think\facade\Request;
use function Sodium\add;

class UserController extends BaseController
{
    /**
     * 需要登录的方法
     */
    protected $NeedLogin = ['addAddress','editAddress','delAddress','getUserAddressList','getOrderList','getUserVisits','delUserVisits','getUserInfo'];

    /**
     * 参数校验规则
     */
    protected $rules = [
        'addaddress'=>[
            'province'=>'require',
            'city'=>'require',
            'district'=>'require',
            'address'=>'require',
            'name'=>'require',
            'mobile'=>'require',
        ],
        'editaddress'=>[
            'addressid'=>'require',
            'province'=>'require',
            'city'=>'require',
            'district'=>'require',
            'address'=>'require',
            'name'=>'require',
            'mobile'=>'require',
        ],
        'deladdress'=>[
            'addressid'=>'require',
        ],
        'getorderlist'=>[
            'type'=>'require',
        ],
    ];

    public function getOrderList(){
        $type = Request::param('type'); //0-全部 1-待付款 2-待发货 3-待收货 4-退款
        $page = Request::param('page') ?? 1;
        $size = Request::param('size') ?? 20;

        $list = [];
        switch ($type){
            case 0:
                $list = ShopSuborderModel::where(['uid'=>$this->userinfo->id])->order(['create_time'=>'desc'])->with('shop,goods')->limit(($page-1)*$size,$size)->select();
                break;
            case 1:
                $list = ShopSuborderModel::where(['uid'=>$this->userinfo->id,'status'=>0])->order(['create_time'=>'desc'])->with('shop,goods')->limit(($page-1)*$size,$size)->select();
                break;
            case 2:
                $list = ShopSuborderModel::where(['uid'=>$this->userinfo->id,'status'=>1,'delivery_status'=>0])->order(['create_time'=>'desc'])->with('shop,goods')->limit(($page-1)*$size,$size)->select();
                break;
            case 3:
                $list = ShopSuborderModel::where(['uid'=>$this->userinfo->id,'delivery_status'=>1])->order(['create_time'=>'desc'])->where('status','<>',3)->with('shop,goods')->limit(($page-1)*$size,$size)->select();
                break;
            case 4:
                $list = ShopOrderReturnModel::where(['uid'=>$this->userinfo->id])->order(['create_time'=>'desc'])->with('goods,suborder,shop')->limit(($page-1)*$size,$size)->select();
                break;
            default:
                break;
        }
        return self::bulidSuccess($list);
    }

    public function addAddress(){
        $address = new UserAddressModel(Request::param());

        Db::startTrans();
        if ($address->is_default == 1){
            //设置其他地址为非默认
            UserAddressModel::update(['is_default'=>0],['uid'=>Request::param('uid')]);
        }
        if ($address->save()){
            Db::commit();
            return self::bulidSuccess();
        }
        Db::rollback();
        return self::bulidFail();
    }

    public function editAddress(){
        $address = UserAddressModel::where(['id'=>Request::param('addressid')])->find();
        if (!$address){
            return self::bulidFail('地址信息有误');
        }
        if ($address->save(Request::param())){
            return self::bulidSuccess();
        }
        return self::bulidFail();
    }

    public function delAddress(){
        $address = UserAddressModel::where(['id'=>Request::param('addressid')])->find();
        if (!$address){
            return self::bulidFail('地址信息有误');
        }
        if ($address->delete()){
            return self::bulidSuccess();
        }
        return self::bulidFail();
    }

    public function getUserAddressList(){
        $addresses = UserAddressModel::where(['uid'=>$this->userinfo->id])->order(['is_default'=>'desc'])->select();
        return self::bulidSuccess($addresses);
    }

    public function getUserVisits(){
        $page = Request::param('page') ?? 1;
        $size = Request::param('size') ?? 20;
        $list = ShopGoodsVisitsModel::where(['uid'=>$this->userinfo->id])->with('goods')->order(['visits_time'=>'desc'])->limit(($page-1)*$size,$size)->select();
        return self::bulidSuccess($list);
    }

    public function delUserVisits(){
        $visitsids = explode(',',Request::param('visitsids'));
        if (ShopGoodsVisitsModel::where(['uid'=>$this->userinfo->id])->where('id','in',$visitsids)->delete()){
            return self::bulidSuccess();
        }
        return self::bulidFail();
    }

    public function getUserInfo(){

        $unpay_count = ShopSuborderModel::where(['uid'=>$this->userinfo->id,'status'=>0])->count("id");
        $undelivery_count = ShopSuborderModel::where(['uid'=>$this->userinfo->id,'status'=>1,'delivery_status'=>0])->count("id");
        $unreceive_count = ShopSuborderModel::where(['uid'=>$this->userinfo->id,'status'=>1,'delivery_status'=>1])->count("id");
        
        $unevaluate_count = ShopOrderGoodsModel::hasWhere('suborder',['status'=>3],'')->where(['ShopOrderGoodsModel.uid'=>$this->userinfo->id,'ShopOrderGoodsModel.evaluate_status'=>0])->count("ShopOrderGoodsModel.id");

        $shop = ShopModel::where(['id'=>$this->userinfo->id])->find();
        $isShop = 0;
        if ($shop){
            $isShop = 1;
        }
        return self::bulidSuccess(['unpay_count'=>$unpay_count,'undelivery_count'=>$undelivery_count,'unreceive_count'=>$unreceive_count,'unevaluate_count'=>$unevaluate_count,'isShop'=>$isShop,'is_anchor'=>$this->userinfo->is_anchor]);
    }
}