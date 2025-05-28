<?php
/**
 * Created by PhpStorm.
 * User: yun7_mac
 * Date: 2019-11-05
 * Time: 15:55
 */

namespace app\admin\controller;


use app\common\model\AdminAuthModel;
use app\common\model\AdminRoleAuthModel;
use app\common\model\StatisticsModel;
use app\common\model\UserModel;
use think\Db;

class IndexController extends BaseController
{
    public function index(){
        $this->assign('config_pub',getConfigPub());
        $this->assign("userinfo",$this->userinfo);

        $menus = [];
        if ($this->userinfo->id == 1 || $this->userinfo->roleid == 1){
            //超管
            $auths_root = AdminAuthModel::where(['parentid'=>0,'status'=>1])->order(['sort'=>'asc','id'=>'asc'])->select();
            $auths_child = AdminAuthModel::where('parentid','<>',0)->where(['status'=>1])->order(['sort'=>'asc','id'=>'asc'])->select();
            foreach ($auths_root as $root){
                if ($root->parentid == 0){
                    $children = [];
                    foreach ($auths_child as $child){
                        if ($child->parentid == $root->id){
                            $children[] = $child;
                        }
                    }
                    $root->children = $children;
                    $menus[] = $root;
                }
            }
        }else{
            $auths_root = AdminRoleAuthModel::hasWhere('auth')->where(['roleid'=>$this->userinfo->roleid,'parentid'=>0,'status'=>1])->with('auth')->order(['authid'=>'asc'])->select();
            $auths_child = AdminRoleAuthModel::hasWhere('auth')->where(['roleid'=>$this->userinfo->roleid,'status'=>1])->where('parentid','<>',0)->with('auth')->order(['authid'=>'asc'])->select();

            foreach ($auths_root as $root){
                if ($root->auth->parentid == 0){
                    $children = [];
                    foreach ($auths_child as $child){
                        if ($child->auth->parentid == $root->auth->id){
                            $children[] = $child->auth;
                        }
                    }
                    $root->auth->children = $children;
                    $menus[] = $root->auth;
                }
            }
        }

        $this->assign('menus',$menus);

        return $this->fetch();
    }

    public function home(){

        //当日统计数据
        $today_s = Db::table('db_statistics')->whereTime('time','today')->find();
        $this->assign('statistics_today',$today_s);

        //昨日统计数据
        $yesterday_s = Db::table('db_statistics')->whereTime('time','yesterday')->find();
        $this->assign('statistics_yesterday',$yesterday_s);

        //月新增用户数
        $month_ios = Db::table('db_statistics')->whereTime('time','month')->sum('regist_ios');
        $month_android = Db::table('db_statistics')->whereTime('time','month')->sum('regist_android');
        $this->assign(['month_ios'=>$month_ios,'month_android'=>$month_android]);

        //全站用户量
        $user_count = Db::table('db_user')->count('id');
        $this->assign('user_count',$user_count);

        //本日营收
        $amount_today = Db::table("db_order")->whereTime('pay_time','today')->sum('pay_amount');
        $this->assign('amount_today',$amount_today);

        //本月营收
        $amount_month = Db::table("db_order")->whereTime('pay_time','month')->sum('pay_amount');
        $this->assign('amount_month',$amount_month);


        //本月走势
        $j = date("t");
        $start_time = strtotime(date('Y-m-01')); //获取本月第一天时间戳
        $month_day_array = array();
        $month_day_ids = [];
        for($i=0;$i<$j;$i++){
            $month_day_array[] = "'".date('m-d',$start_time+$i*86400)."'"; //每隔一天赋值给数组
            $month_day_ids[] = date('Ymd',$start_time+$i*86400);
        }
        $this->assign('month_day',implode(',',$month_day_array));

        $month_s = Db::table('db_statistics')->whereTime('time','>',date('Y-m-01'))->select();
        $month_regist_array = [];
        $month_launch_array = [];
        $month_activity_array = [];
        foreach ($month_day_ids as $index=>$value){
            $regist_count = 0;
            $launch_count = 0;
            $activity_count = 0;
            foreach ($month_s as $statistics){
                if ($statistics['id'] == $value){
                    $regist_count = $statistics['regist_ios']+$statistics['regist_android'];
                    $launch_count = $statistics['launch_ios']+$statistics['launch_android'];
                    $activity_count = $statistics['activity_ios']+$statistics['activity_android'];
                }
            }
            $month_regist_array[] = $regist_count;
            $month_launch_array[] = $launch_count;
            $month_activity_array[] = $activity_count;
        }
        $this->assign('month_regist_array',implode(',',$month_regist_array));
        $this->assign('month_launch_array',implode(',',$month_launch_array));
        $this->assign('month_activity_array',implode(',',$month_activity_array));

        //用户平台统计
        $user_count_ios = UserModel::where(['login_platform'=>1])->count('id');
        $user_count_android = UserModel::where(['login_platform'=>2])->count('id');
        $user_count_web = UserModel::where(['login_platform'=>0])->count('id');
        $this->assign('user_platform_count',"{value:{$user_count_ios}, name:'iOS'},{value:{$user_count_android}, name:'安卓'},{value:{$user_count_web}, name:'PC'}");

        //数据分析
        $order_succ_count_month = Db::table('db_order')->whereTime('create_time','month')->where('pay_status',1)->count('id');
        $order_count_month = Db::table('db_order')->whereTime('create_time','month')->count('id');
        $order_succ_rate_month = $order_succ_count_month == 0 ? 0 : round($order_succ_count_month / $order_count_month * 100);
        $this->assign('order_succ_rate_month',$order_succ_rate_month);

        $order_succ_count = Db::table('db_order')->where('pay_status',1)->count('id');
        $order_count = Db::table('db_order')->count('id');
        $order_succ_rate = $order_count == 0 ? 0 : round($order_succ_count / $order_count * 100);
        $this->assign('order_succ_rate',$order_succ_rate);

        $user_online_count = UserModel::where(['online_status'=>1])->count('id') ?? 0;
        $user_online_rate = $user_online_count > 0 ? round($user_online_count / $user_count * 100) : 0;
        $this->assign('user_online_rate',$user_online_rate);

        $anchor_online_count = UserModel::where(['online_status'=>1,'is_anchor'=>1])->count('id');
        $anchor_count = UserModel::where(['is_anchor'=>1])->count('id') ?? 0;

        $anchor_online_rate = $anchor_count > 0 ? round($anchor_online_count / $anchor_count * 100) : 0;
        $this->assign('anchor_online_rate',$anchor_online_rate);

        return $this->fetch();
    }

    public function noOperation(){
        return json(["code"=>0,"msg"=>""]);
    }

}