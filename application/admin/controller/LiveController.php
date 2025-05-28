<?php
/**
 * Created by PhpStorm.
 * User: yun7_mac
 * Date: 2019-11-25
 * Time: 08:55
 */

namespace app\admin\controller;


use app\common\model\AnchorIncomeModel;
use app\common\model\ConnectModel;
use app\common\model\GiftLogModel;
use app\common\model\GuildModel;
use app\common\model\GuildProfitModel;
use app\common\model\LiveCategoryModel;
use app\common\model\LiveHistoryModel;
use app\common\model\LiveModel;
use app\common\model\UserModel;
use app\common\TXService;
use think\Db;
use think\facade\Env;
use think\facade\Request;

class LiveController extends BaseController
{
    public function index(){
        $categorys = LiveCategoryModel::order('sort','asc')->order('id','desc')->select();
        $this->assign('categorys',$categorys);
        return $this->fetch();
    }

    public function dashboard(){
        $page = Request::param('page') ?? 1;
        $this->assign('page',$page);
        $lives = LiveModel::with('anchor')->order(['start_time'=>'desc'])->limit(($page-1)*12,12)->select();
        $this->assign('lives',$lives);
        $count = LiveModel::count('*');
        $this->assign('count',$count);
        return $this->fetch();
    }

    public function add(){
        $categorys = LiveCategoryModel::order('sort','asc')->order('id','desc')->select();
        $this->assign('categorys',$categorys);
        return $this->fetch();
    }

    public function logs(){
        $categorys = LiveCategoryModel::order('sort','asc')->order('id','desc')->select();
        $this->assign('categorys',$categorys);
        return $this->fetch();
    }

    public function category(){
        return $this->fetch();
    }

    public function category_add(){
        return $this->fetch();
    }

    public function category_edit(){
        $id = Request::param('id');
        $category = LiveCategoryModel::get($id);
        if (!$category){
            $this->redirect('/admin/live/index');
        }
        $this->assign('category',$category);
        return $this->fetch();
    }

    public function getlives(){
        $anchorid = Request::param("anchorid");
        $categoryid = Request::param("categoryid");
        $where = [];
        if ($anchorid){
            $where["anchorid"] = $anchorid;
        }
        if ($categoryid){
            $where["categoryid"] = $categoryid;
        }

        $page = Request::param("page");
        $limit = Request::param("limit");

        $logs = LiveModel::where($where)->with(['anchor' => function($query){
            $query->field('id, nick_name');
        }, 'category'])->limit(($page-1)*$limit,$limit)->order("start_time","desc")->select();

        if (count($logs)>0){
            $count = LiveModel::where($where)->count("anchorid");
            return json(["code"=>0,"msg"=>"","data"=>$logs,"count"=>$count]);
        }
        return self::bulidFail("未查询到相关数据");
    }

    public function getlogs(){
        $anchorid = Request::param("anchorid");
        $categoryid = Request::param("categoryid");
        $liveid = Request::param("liveid");
        $where = [];
        if ($anchorid){
            $where["anchorid"] = $anchorid;
        }
        if ($categoryid){
            $where["categoryid"] = $categoryid;
        }
        if ($liveid){
            $where["liveid"] = $liveid;
        }

        $page = Request::param("page");
        $limit = Request::param("limit");

        $logs = LiveHistoryModel::where($where)->with(['anchor' => function($query){
            $query->field('id, nick_name');
        }, 'category'])->limit(($page-1)*$limit,$limit)->order("start_time","desc")->select();

        if (count($logs)>0){
            $count = LiveHistoryModel::where($where)->count("id");
            return json(["code"=>0,"msg"=>"","data"=>$logs,"count"=>$count]);
        }
        return self::bulidFail("未查询到相关数据");
    }

    public function add_post(){
        $param = Request::param();
        $anchor = UserModel::where(['id'=>$param['anchorid']])->find();
        if (!$anchor){
            return self::bulidFail('主播ID有误');
        }
        $is_live = LiveModel::where('anchorid',$param['anchorid'])->value('anchorid');
        if($is_live){
            return self::bulidFail('当前主播正在直播中，请勿重复添加');
        }
        $live = new LiveModel($param);
        $live->start_stamp = time();
        $live->start_time = nowFormat();
        $live->isvideo = 1;
        $live->liveid = time().substr(strval($live->anchorid),4,4);
        if ($live->room_type == 1){
            $live->password = md5($live->password);
        }
        $live->rec_weight = $anchor->rec_weight;
        if ($live->save()){
            return self::bulidSuccess();
        }
        return self::bulidFail();
    }

    public function stop_post(){
        $liveid = Request::param('liveid');
        $live = LiveModel::where(['liveid'=>$liveid])->find();

        Db::startTrans();
        $anchor = UserModel::where(['id'=>$live->anchorid])->find();

        //计算收益
        $profit = GiftLogModel::where(['liveid'=>$live->liveid])->sum('spend');
        $liveHistory = new LiveHistoryModel(['anchorid'=>$anchor->id,'liveid'=>$live->liveid,'title'=>$live->title,'stream'=>$live->stream,'pull_url'=>$live->pull_url,'categoryid'=>$live->categoryid,'orientation'=>$live->orientation,'start_stamp'=>$live->start_stamp,'end_stamp'=>time(),'start_time'=>$live->start_time,'end_time'=>nowFormat(),'gift_profit'=>$profit]);

        //公会分红
        $guildup = true;
        if ($anchor->guildid){
            $guild = GuildModel::get($anchor->guildid);
            if ($guild){
                $guild_diamond_get = round($profit * ($guild->sharing_ratio - $anchor->sharing_ratio) / 100);
                $guild->diamond = ['inc', $guild_diamond_get];
                $guild->diamond_total = ['inc', $guild_diamond_get];

                //写入公会收益记录
                $guild_profit = new GuildProfitModel(['guildid'=>$guild->id, 'diamond'=>$guild_diamond_get, 'content'=>"旗下主播(ID:{$anchor->id})直播(ID:{$live->liveid})收益分红：{$guild_diamond_get}钻石", 'create_time'=>nowFormat()]);
                if(!$guild->save() || !$guild_profit->save()){
                    $guildup = false;
                }
            }
        }

        //写入主播当日收入统计 用于排行榜
        $anchorIncome = AnchorIncomeModel::where(['anchorid'=>$live->anchorid])->whereTime('date','today')->find();
        if (!$anchorIncome){
            $anchorIncome = new AnchorIncomeModel(['anchorid'=>$live->anchorid,'income'=>0,'date'=>date('Y-m-d')]);
        }
        $anchorIncome->income = ['inc',$profit];

        if ($live->delete() && $liveHistory->save() && $anchorIncome->save() && $guildup) {
            Db::commit();
            //通知群内成员
            $endelem = TXService::buildCustomElem('LiveFinished');
            TXService::sendChatRoomMsg(getAnchorRoomID($live->anchorid),$endelem);
            return self::bulidSuccess($liveHistory);
        }else{
            Db::rollback();
            return self::bulidFail();
        }
    }

    public function getcategorys(){
        $categorys = LiveCategoryModel::order('sort','asc')->order('id','desc')->select();
        if (count($categorys))
            return self::bulidSuccess($categorys);
        return self::bulidFail("未查询到相关数据");
    }

    public function category_add_post(){
        $category = new LiveCategoryModel(Request::param());
        if ($category->save()){
            return self::bulidSuccess();
        }
        return self::bulidFail();
    }

    public function category_edit_post(){
        $id = Request::param('id');
        $category = LiveCategoryModel::get($id);
        if ($category->save(Request::except('id'))){
            return self::bulidSuccess();
        }
        return self::bulidFail();
    }

    public function category_hide_post(){
        if (LiveCategoryModel::update(['status'=>0],['id'=>Request::param('id')])){
            return self::bulidSuccess();
        }
        return self::bulidFail();
    }
    public function category_show_post(){
        if (LiveCategoryModel::update(['status'=>1],['id'=>Request::param('id')])){
            return self::bulidSuccess();
        }
        return self::bulidFail();
    }

    public function signForVod(){
        $configPri = getConfigPri();
        // 确定签名的当前时间和失效时间
        $current = time();
        $expired = $current + 86400;  // 签名有效期：1天

        // 向参数列表填入参数
        $arg_list = array(
            "secretId" => $configPri['qcloud_secretid'],
            "currentTimeStamp" => $current,
            "expireTime" => $expired,
            "random" => rand(),
            "oneTimeValid" => 1);

        // 计算签名
        $orignal = http_build_query($arg_list);
        $signature = base64_encode(hash_hmac('SHA1', $orignal, $configPri['qcloud_secretkey'], true).$orignal);
        return self::bulidSuccess(['sign'=>$signature]);
    }
}