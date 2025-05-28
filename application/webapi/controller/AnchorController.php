<?php


namespace app\webapi\controller;


use app\common\model\AnchorFansModel;
use app\common\model\ConfigTagModel;
use app\common\model\GiftLogModel;
use app\common\model\IntimacyModel;
use app\common\model\MomentModel;
use app\common\model\UserModel;
use app\common\model\VisitorLogModel;
use think\facade\Request;

class AnchorController extends BaseController
{
    protected $NeedLogin = ['getAttentAnchors', 'attentAnchor', 'addVisitorLog','checkAttent'];

    protected $rules = array(

        //关注主播列表
        'getattentanchors'=>array(
        ),

        //主播详情
        'getanchorinfo'=>array(
            'anchorid'=>'require'
        ),

        //主播基础信息
        'getanchorbasicinfo'=>array(
            'anchorid'=>'require'
        ),

        //用户评价列表
        'getuserevaluates'=>array(
            'anchorid'=>'require'
        ),

        //关注、取消关注主播
        'attentanchor'=>array(
            'anchorid'=>'require',
            'type'=>'require' //1关注 0-取消
        ),
        //写入访客记录
        'addvisitorlog'=>array(
            'anchorid'=>'require'
        ),
        //检测是否已关注
        'checkattent'=>[
            'anchorid'=>'require'
        ],
    );

    public function initialize()
    {
        parent::initialize();
    }

    //主播详情
    public function getAnchorInfo(){
        $anchorid = Request::post("anchorid");
        $uid = Request::post("uid");
        $anchor = UserModel::where(['id'=>$anchorid, 'status'=>0])->with(['profile','live'])->field(['password','token','wx_unionid'],true)->find();
        if (!$anchor){
            return self::bulidFail("主播不存在或账号状态异常");
        }else{
            if ($this->userinfo){
                //写入访客记录
                $visitorlog = new VisitorLogModel(['uid'=>$anchorid, 'visitorid'=>$this->userinfo->id, 'create_time'=>nowFormat()]);
                if($visitorlog->save()) {
                    $visitorCount = VisitorLogModel::where('uid', $anchorid)->count();
                    setVisitorCount($anchorid, $visitorCount);
                }
            }

            $anchor = $anchor->toArray();
            if ($uid){
                //查询是否已关注
                $checkAttent = AnchorFansModel::where(['anchorid' => $anchorid, 'fansid' => Request::post("uid")])->find();
                if ($checkAttent) {
                    $anchor['isattent'] = 1;
                } else {
                    $anchor['isattent'] = 0;
                }
            }
            if ($anchor['tags']){
                //主播形象标签
                $anchor_tags = explode(',',$anchor['tags']);
                $tags = ConfigTagModel::where('id', 'in', $anchor_tags)->select();
                $anchor['tags'] = $tags;
            }

            //关注数量
            $anchor['attent_count'] = count(getUserAttentAnchorIds($anchorid));
            //粉丝数量
            $anchor['fans_count'] = getFansCount($anchorid);
            //送礼数量
            $anchor['gift_spend'] = getSendGiftCount($anchorid);
            //动态数量
            $anchor['moment_count'] = MomentModel::where(['uid'=>$anchorid,'status'=>1])->count("id");

            //礼物展示
//            $gift_recive = GiftLogModel::where('anchorid', $anchorid)->with(['gift'])->order('spend desc')->limit(0,5)->select();
//            foreach ($gift_recive as $gift){
//                $anchor['gift_show'][] = $gift->gift;
//            }

            //亲密榜展示
//            $intimacys = IntimacyModel::where('anchorid', $anchorid)->with(['user'=>function($query){
//                $query->field(['id','nick_name','avatar']);
//            }])->order('intimacy desc')->limit(0,5)->select();
//            $anchor['intimacys'] = $intimacys;

            return self::bulidSuccess($anchor);
        }
    }

    //关注主播列表
    public function getAttentAnchors(){
        $page = Request::post("page") ?? 1;
        $size = Request::post("size") ?? 20;

        $attents = AnchorFansModel::where(['fansid'=>$this->userinfo->id])->with('anchor')->order('create_time','desc')->limit(($page-1)*$size,$size)->select();

        foreach ($attents as $index=>$attent){
            if ($attent->anchor)
                $attent->anchor->isattent = 1;
        }

        $total_count = AnchorFansModel::where(['fansid'=>$this->userinfo->id])->count('id');
        return self::bulidSuccess(['list'=>$attents,'total_count'=>$total_count]);
    }

    //关注、取消关注主播
    public function attentAnchor(){
        $anchorid = Request::post("anchorid");
        if ($anchorid == $this->userinfo->id){
            return self::bulidFail("无法关注自己");
        }
        $anchor = UserModel::where(['id'=>$anchorid])->find();
        if (!$anchor){
            return self::bulidFail('主播账户状态异常');
        }
        $type = Request::post("type");
        $attent = AnchorFansModel::where(['fansid'=>$this->userinfo->id, 'anchorid'=>$anchorid])->find();
        if ($attent && $type == 0){
            //取消关注
            if ($attent->delete()){
                //更新redis缓存
                $attentids = getUserAttentAnchorIds($this->userinfo->id);
                if ($attentids && in_array($anchorid, $attentids)){
                    $attentids = array_diff($attentids, [$anchorid]);
                }
                setUserAttentAnchorIds($this->userinfo->id, $attentids);

                $fans_count = AnchorFansModel::where('anchorid', $anchorid)->count();
                setFansCount($anchorid, $fans_count);
                return self::bulidSuccess(['fans_count'=>$fans_count]);
            }
        }else if ($attent){
            $fans_count = AnchorFansModel::where('anchorid', $anchorid)->count();
            setFansCount($anchorid, $fans_count);
            return self::bulidSuccess(['fans_count'=>$fans_count]);
        }else{
            $attent = new AnchorFansModel(['fansid'=>$this->userinfo->id, 'anchorid'=>$anchorid, 'create_time'=>nowFormat()]);
            //关注
            if ($attent->save()){
                //更新redis缓存
                $attentids = getUserAttentAnchorIds($this->userinfo->id);
                if ($attentids && !in_array($anchorid,$attentids)){
                    $attentids[] = $anchorid;
                }
                setUserAttentAnchorIds($this->userinfo->id, $attentids);

                $fans_count = AnchorFansModel::where('anchorid', $anchorid)->count();
                setFansCount($anchorid, $fans_count);
                return self::bulidSuccess(['fans_count'=>$fans_count]);
            }
        }
        return self::bulidFail();
    }

    public function addVisitorLog(){
        $anchorid = Request::post("anchorid");
        //写入访客记录
        $visitorlog = new VisitorLogModel(['uid'=>$anchorid, 'visitorid'=>$this->userinfo->id, 'create_time'=>nowFormat()]);
        if($visitorlog->save()){
            $visitorCount = VisitorLogModel::where('uid', $anchorid)->count();
            setVisitorCount($anchorid,$visitorCount);
            return self::bulidSuccess([]);
        }
        return self::bulidFail();
    }
}