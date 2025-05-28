<?php


namespace app\webapi\controller;


use app\common\model\ActivityModel;
use app\common\model\AnchorIncomeModel;
use app\common\model\LiveCategoryModel;
use app\common\model\LiveModel;
use app\common\model\UserModel;
use think\facade\Request;

class HomeController extends BaseController
{
    public function getHomeData(){
        $recommend_lives = LiveModel::order(['rec_weight'=>'desc','hot'=>'desc','start_stamp'=>'desc'])->limit(0,5)->select();
        $hot_lives = LiveModel::order(['rec_weight'=>'desc','hot'=>'desc','start_stamp'=>'desc'])->limit(5,12)->select();
        $activities = ActivityModel::where(['type'=>1])->order('modify_time','desc')->limit(0,3)->select();
        $actids = [];
        foreach ($activities as $activity){
            $actids[] = $activity->id;
        }
        $notices = ActivityModel::where('id','not in',$actids)->order('modify_time','desc')->limit(0,5)->select();
        foreach ($notices as $notice){
            $notice->content = strip_tags($notice->content);
        }

        $recoomend_anchors = UserModel::with('profile')->order(['rec_weight'=>'desc','anchor_point'=>'desc'])->limit(0,7)->select();
        foreach ($recoomend_anchors as $anchor){
            $anchor->is_attent = 0;
        }
        if ($uid = Request::param('uid')){
            $attentedids = getUserAttentAnchorIds($uid);
            foreach ($attentedids as $attentid){
                foreach ($recoomend_anchors as $index=>$anchor){
                    if ($anchor->id == $attentid){
                        $anchor->is_attent = 1;
                    }
                }
            }
        }

        $categorys = LiveCategoryModel::select();
        foreach ($categorys as $category){
            $lives =  LiveModel::where('categoryid',$category->id)->with('anchor')->order(['rec_weight'=>'desc','hot'=>'desc','start_stamp'=>'desc'])->limit(0,5)->select();
            $category->lives = $lives;
        }

        $anchor_rank = AnchorIncomeModel::with('anchor')->whereTime('date', 'week')
            ->orderRaw("sum(income) desc")->field(['id','anchorid',"sum(income) income"])->group('anchorid')->select();

        return self::bulidSuccess(['recommend_lives'=>$recommend_lives,'hot_lives'=>$hot_lives,'activities'=>$activities,'notices'=>$notices,'recoomend_anchors'=>$recoomend_anchors,'categorys'=>$categorys,'anchor_rank'=>$anchor_rank]);
    }
}