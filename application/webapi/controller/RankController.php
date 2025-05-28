<?php


namespace app\webapi\controller;


use app\common\model\AnchorIncomeModel;
use app\common\model\UserConsumeModel;
use app\common\model\UserModel;
use think\facade\Request;

class RankController extends BaseController
{
    protected $NeedLogin = [];

    protected $rules = array(

        //主播排行榜
        'getanchorranklist'=>[
            'type'=>'require' //0-日榜 1-周榜 2-月榜 3-总榜
        ],
        //用户排行榜
        'getuserranklist'=>[
            'type'=>'require' //0-日榜 1-周榜 2-月榜 3-总榜
        ],
    );

    public function getAnchorRankList(){
        //测试用数据
        //return self::bulidSuccess(self::createTestAnchorIncomeModels());

        $type = Request::param('type');
        switch ($type){
            case 0:
                $date = 'today';
                break;
            case 1:
                $date = 'week';
                break;
            case 2:
                $date = 'month';
                break;
            default:
                $date = '';
                break;
        }
        if ($date){
            $list = AnchorIncomeModel::with('anchor')->whereTime('date', $date)
                ->orderRaw("sum(income) desc")->field(['id','anchorid',"sum(income) income"])->group('anchorid')->limit(0,50)->select();
        }else{
            $list = AnchorIncomeModel::with('anchor')->orderRaw("sum(income) desc")->field(['id','anchorid',"sum(income) income"])->group('anchorid')->limit(0,50)->select();
        }

        if ($uid = Request::post("uid")){
            $attentids = getUserAttentAnchorIds($uid);
            foreach ($attentids as $attentid){
                foreach ($list as $index=>$item){
                    if ($item->anchor->id == $attentid){
                        $item->anchor->isattent = 1;
                    }
                }
            }
        }

        return self::bulidSuccess($list);
    }

    public function getUserRankList(){
        //测试用数据
        //return self::bulidSuccess(self::createTestUserConsumeModels());

        $type = Request::param('type');
        switch ($type){
            case 0:
                $date = 'today';
                break;
            case 1:
                $date = 'week';
                break;
            case 2:
                $date = 'month';
                break;
            default:
                $date = '';
                break;
        }
        if ($date){
            $list = UserConsumeModel::with('user')->whereTime('date', $date)
                ->orderRaw("sum(consume) desc")->field(['id','uid',"sum(consume) consume"])->group('uid')->limit(0,50)->select();
        }else{
            $list = UserConsumeModel::with('user')->orderRaw("sum(consume) desc")->field(['id','uid',"sum(consume) consume"])->group('uid')->limit(0,50)->select();
        }

        if ($uid = Request::post("uid")){
            $attentids = getUserAttentAnchorIds($uid);
            foreach ($attentids as $attentid){
                foreach ($list as $index=>$item){
                    if ($item->user->id == $attentid){
                        $item->user->isattent = 1;
                    }
                }
            }
        }

        return self::bulidSuccess($list);
    }

    public function createTestAnchorIncomeModels(){
        $users = UserModel::field("id, nick_name, user_level, avatar, is_anchor, vip_date, vip_level, anchor_level")->order(['vip_date'=>'desc'])->with('profile')->limit(0,50)->select();
        $data = [];
        $income = 20200618;
        foreach ($users as $user){
            $anchorIncomeModel = new AnchorIncomeModel(['anchorid'=>$user->id,'income'=>$income]);
            $anchorIncomeModel->anchor = $user;
            $income -= 200;
            $data[] = $anchorIncomeModel;
        }
        if ($uid = Request::post("uid")){
            $attentids = getUserAttentAnchorIds($uid);
            foreach ($attentids as $attentid){
                foreach ($data as $index=>$item){
                    if ($item->anchor->id == $attentid){
                        $item->anchor->isattent = 1;
                    }
                }
            }
        }
        return $data;
    }

    public function createTestUserConsumeModels(){
        $users = UserModel::field("id, nick_name, user_level, avatar, is_anchor, vip_date, vip_level, anchor_level")->order(['vip_date'=>'desc'])->with('profile')->limit(0,50)->select();
        $data = [];
        $consume = 20200618;
        foreach ($users as $user){
            $userComsumeModel = new UserConsumeModel(['uid'=>$user->id,'consume'=>$consume]);
            $userComsumeModel->user = $user;
            $consume -= 200;
            $data[] = $userComsumeModel;
        }
        if ($uid = Request::post("uid")){
            $attentids = getUserAttentAnchorIds($uid);
            foreach ($attentids as $attentid){
                foreach ($data as $index=>$item){
                    if ($item->user->id == $attentid){
                        $item->user->isattent = 1;
                    }
                }
            }
        }
        return $data;
    }
}