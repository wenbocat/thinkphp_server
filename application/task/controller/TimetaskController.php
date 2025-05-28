<?php


namespace app\task\controller;


use app\common\model\ShopGoodsInventoryModel;
use app\common\model\ShopGoodsModel;
use app\common\model\ShopSuborderModel;
use app\common\model\UserModel;
use app\common\model\UserRecModel;
use think\Controller;
use think\Db;

class TimetaskController extends Controller
{
    /*
     *  用户定时推荐，结束推荐
     */
    public function AnchorRecommendWeightTask(){
        $unends = UserRecModel::where(['end_status'=>0])->where('end_time','<',nowFormat())->select();
        foreach ($unends as $unend){
            $unend->end_status = 1;
            Db::startTrans();
            if ($unend->save() && UserModel::update(['rec_weight'=>0],['id'=>$unend->uid])){
                Db::commit();
            }
            Db::rollback();
        }

        $unstarts = UserRecModel::where('start_time','<',nowFormat())->where(['start_status'=>0,'end_status'=>0])->where('end_time','>',nowFormat())->select();
        foreach ($unstarts as $unstart){
            $unstart->start_status = 1;
            Db::startTrans();
            if ($unstart->save() && UserModel::update(['rec_weight'=>$unstart->rec_weight],['id'=>$unstart->uid])){
                Db::commit();
            }
            Db::rollback();
        }
        return json(['status'=>'AnchorRecommendWeightTask Success']);
    }
    
    /*
     * 定时扫描订单，处理未支付订单
     */
    public function ShopOrderTimeOut(){
        $time = time() - 14*60;
        $orders = ShopSuborderModel::where(['status'=>0])->where('create_time','<',date('Y-m-d H:i:s',$time))->with('goods')->select();
        foreach ($orders as $order){
            Db::startTrans();
            $goodsRes = true;
            $goodsArr = $order->goods;
            foreach ($goodsArr as $goods_in){
                $res1 = ShopGoodsInventoryModel::update(['left_count'=>['inc',$goods_in->count],'sale_count'=>['dec',$goods_in->count]],['id'=>$goods_in->inventoryid]);
                $res2 = ShopGoodsModel::update(['sale_count'=>$goods_in->count],['id'=>$goods_in->goodsid]);
                if (!$res1 || !$res2){
                    Db::rollback();
                    $goodsRes = false;
                    echo '订单关闭失败:'.$order->id;
                }
            }
            if ($goodsRes){
                if ($order->save(['status'=>2])){
                    Db::commit();
                    echo '订单关闭成功:'.$order->id;
                }else{
                    Db::rollback();
                    echo '订单关闭失败:'.$order->id;
                }
            }
        }
    }
}