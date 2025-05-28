<?php


namespace app\webapi\controller;

use app\common\model\AnchorFansModel;
use app\common\model\GuildModel;
use app\common\model\GuildProfitModel;
use app\common\model\IntimacyModel;
use app\common\model\MomentCollectModel;
use app\common\model\MomentCommentLikeModel;
use app\common\model\MomentCommentModel;
use app\common\model\MomentLikeModel;
use app\common\model\MomentModel;
use app\common\model\MomentUnlockModel;
use app\common\model\UserModel;
use app\common\model\UserProfitModel;
use think\Db;
use think\facade\Request;

class MomentController extends BaseController
{
    protected $NeedLogin = ['getAttentList', 'unlockMoment', 'likeMoment', 'collectMoment', 'likeComment', 'publish', 'getTempKeysForCos', 'getSignForVod', 'publishComment', 'getCollection', 'getLike'];

    protected $rules = array(
        //解锁
        'unlockmoment'=>[
            'momentid'=>'require'
        ],
        //点赞
        'likemoment'=>[
            'momentid'=>'require'
        ],
        //收藏
        'collectmoment'=>[
            'momentid'=>'require',
            'type'=>'require' //1-收藏 0-取消收藏
        ],
        //评论列表
        'getcomments'=>[
            'momentid'=>'require'
        ],
        //评论点赞
        'likecomment'=>[
            'commentid'=>'require'
        ],
        //发动态
        'publish'=>[
            'type'=>'require'
        ],
        //发动态
        'publishcomment'=>[
            'momentid'=>'require',
            'content'=>'require'
        ],
        'getlistbyuser'=>[
            'authorid'=>'require'
        ],
    );

    public function initialize()
    {
        parent::initialize();
    }

    //热门动态
    public function getHotList(){
        $page = Request::post("page") ?? 1;
        $size = Request::post("size") ?? 20;
        $moments = MomentModel::where(['status'=>1, 'is_secret'=>0])->with(['user'])->order(['recommend'=>'desc','comment_count'=>'desc','create_time'=>'desc'])->limit(($page-1)*$size,$size)->select();

        return self::bulidSuccess(self::handlerData($moments,Request::param('uid')));
    }

    //最新动态
    public function getNewestList(){
        $page = Request::post("page") ?? 1;
        $size = Request::post("size") ?? 20;
        $moments = MomentModel::where(['status'=>1, 'is_secret'=>0])->with(['user'])->order(['create_time'=>'desc'])->limit(($page-1)*$size,$size)->select();

        return self::bulidSuccess(self::handlerData($moments,Request::param('uid')));
    }

    //关注用户发布的动态
    public function getAttentList(){
        $attents = AnchorFansModel::where(['fansid'=>$this->userinfo->id])->select();
        $ids = [];
        foreach ($attents as $attent){
            $ids[] = $attent->anchorid;
        }
        if (!count($ids)){
            return self::bulidSuccess([]);
        }
        $page = Request::post("page") ?? 1;
        $size = Request::post("size") ?? 20;
        $moments = MomentModel::where(['status'=>1, 'is_secret'=>0])->where('uid','in', $ids)->with(['user'])->order(['recommend'=>'desc','create_time'=>'desc'])->limit(($page-1)*$size,$size)->select();

        return self::bulidSuccess(self::handlerData($moments,Request::param('uid')));
    }


    public function getUserMomentList(){
        $authorid = Request::param('authorid');
        $page = Request::post("page") ?? 1;
        $size = Request::post("size") ?? 20;
        $moments = MomentModel::where(['status'=>1, 'is_secret'=>0, 'uid'=>$authorid])->with(['user'])->order(['recommend'=>'desc','create_time'=>'desc'])->limit(($page-1)*$size,$size)->select();

        return self::bulidSuccess(self::handlerData($moments,Request::param('uid')));
    }

    //获取用户收藏
    public function getCollection(){
        $page = Request::post("page") ?? 1;
        $size = Request::post("size") ?? 20;
        $collects = MomentCollectModel::where(['uid'=>$this->userinfo->id])->with('moment')->order(['create_time'=>'desc'])->limit(($page-1)*$size,$size)->select();
        $moments = [];
        foreach ($collects as $collect){
            $collect->moment->collected = 1;
            $moments[] = $collect->moment;
        }

        return self::bulidSuccess(self::handlerData($moments,Request::param('uid')));
    }

    //获取用户点赞
    public function getLike(){
        $page = Request::post("page") ?? 1;
        $size = Request::post("size") ?? 20;
        $likes = MomentLikeModel::hasWhere('moment')->where(['MomentLikeModel.uid'=>$this->userinfo->id])->with('moment')->order(['MomentLikeModel.create_time'=>'desc'])->limit(($page-1)*$size,$size)->select();
        $moments = [];
        foreach ($likes as $like){
            $like->moment->collected = 1;
            $moments[] = $like->moment;
        }

        return self::bulidSuccess(self::handlerData($moments,Request::param('uid')));
    }

    static private function handlerData($moments,$uid=null){
        foreach ($moments as $moment){
            $moment->unlocked = 0;
        }
        if ($uid){
            $id_arr = [];
            foreach ($moments as $moment) {
                if ($moment->unlock_price > 0) {
                    $id_arr[] = $moment->id;
                }
            }
            $unlocks = MomentUnlockModel::where('uid', $uid)->where('momentid', 'in', $id_arr)->select();
            foreach ($unlocks as $unlock) {
                foreach ($moments as $moment) {
                    if ($moment->unlock_price > 0 && $moment->id == $unlock->momentid) {
                        $moment->unlocked = 1;
                    }
                }
            }
            $likedids = getUserLikeMomentIds($uid);
            foreach ($likedids as $likedid) {
                foreach ($moments as $moment) {
                    if ($moment->id == $likedid) {
                        $moment->liked = 1;
                    }
                }
            }
            $collids = getUserCollectMomentIds($uid);
            foreach ($collids as $collid) {
                foreach ($moments as $moment) {
                    if ($moment->id == $collid) {
                        $moment->collected = 1;
                    }
                }
            }
        }
        foreach ($moments as $moment){
            if ($moment->unlock_price > 0 && !$moment->unlocked && $moment->uid != $uid){
                if ($moment->type == 2){
                    //图文
                    $moment->image_url = "";
                }elseif ($moment->type == 3){
                    $moment->video_url = "";
                }
            }
        }
        return $moments;
    }

    //发布动态
    public function publish(){
        $type = Request::param('type');
        $title = Request::param('title');
        $image_url = Request::param('image_url');
        $blur_image_url = Request::param('blur_image_url');
        $video_url = Request::param('video_url');
        $single_display_type = Request::param('single_display_type');
        $unlock_price = Request::param('unlock_price');

        if ($type == 1 && !$title){
            return self::bulidFail('参数不全');
        }
        if ($type == 2 && !$image_url){
            return self::bulidFail('参数不全');
        }
        if ($type == 2){
            $imgArr = explode(',',$image_url);
            if (count($imgArr) == 1 && !$single_display_type){
                return self::bulidFail('单图模式请选择图片展示形式');
            }
        }
        if ($type == 3 && (!$image_url || !$video_url || !$single_display_type)){
            return self::bulidFail('参数不全');
        }

        $status = 1;
        $configPri = getConfigPri();
        if ($configPri->switch_moment_check){
            $status = 0;
            //增加用户经验
            $user = $this->userinfo;
            $userpoint = $user->point + addUserPointEventPublishMoment();
            $user->point = ['inc', addUserPointEventPublishMoment()];
            //更新用户等级
            $user->user_level = calculateUserLevel($userpoint);
            $user->save();
        }

        $moment = new MomentModel(['uid'=>$this->userinfo->id, 'type'=>$type, 'title'=>$title, 'image_url'=>$image_url, 'video_url'=>$video_url, 'blur_image_url'=>$blur_image_url, 'single_display_type'=>$single_display_type, 'unlock_price'=>$unlock_price, 'status'=>$status, 'create_time'=>nowFormat()]);
        if ($moment->save()){
            return self::bulidSuccess();
        }
        return self::bulidFail();
    }

    public function unlockMoment(){
        $momentid = Request::param('momentid');
        $moment = MomentModel::where(['id'=>$momentid])->find();
        if (!$moment){
            return self::bulidFail('该动态状态异常，无法解锁');
        }
        if ($moment->uid == $this->userinfo->id){
            return self::bulidSuccess(); //自己的动态无需解锁
        }
        $anchor = UserModel::get($moment->uid);
        $user = $this->userinfo;

        if ($moment->unlock_price > $user->gold){
            return self::bulidChargeFail();
        }

        $unlockModel = new MomentUnlockModel(['uid'=>$user->id,'momentid'=>$momentid,'create_time'=>nowFormat()]);
        Db::startTrans();
        if (!$unlockModel->save()){
            return self::bulidFail('解锁失败');
        }

        $moment->watch_count = ['inc', 1];
        $moment->save();

        //金币钻石结算
        $user->gold = ['dec', $moment->unlock_price];
        $anchor_diamond_get = round($moment->unlock_price * $anchor->sharing_ratio / 100);
        $anchor->diamond = ['inc', $anchor_diamond_get];
        $anchor->diamond_total = ['inc', $anchor_diamond_get];

        //公会收益结算
        $guildup = 1;
        if ($anchor->guildid){
            $guild = GuildModel::get($anchor->guildid);
            if ($guild){
                $guild_diamond_get = round($moment->unlock_price * $guild->sharing_ratio / 100) - $anchor_diamond_get;
                $guild->diamond = ['inc', $guild_diamond_get];
                $guild->diamond_total = ['inc', $guild_diamond_get];

                //写入公会收益记录
                $guild_profit = new GuildProfitModel(['guildid'=>$guild->id, 'diamond'=>$guild_diamond_get, 'content'=>"主播(ID:{$anchor->id})动态(ID:{$moment->id})被解锁(ID:{$unlockModel->id})分红：{$guild_diamond_get}钻石", 'create_time'=>nowFormat()]);
                if(!$guild->save() || !$guild_profit->save()){
                    $guildup = 0;
                }
            }
        }

        //写入主播收益记录
        $anchor_profit = new UserProfitModel(['uid'=>$anchor->id, 'coin_count'=>$anchor_diamond_get, 'content'=>"动态(ID:{$moment->id})被解锁(ID:{$unlockModel->id})收益：{$anchor_diamond_get}钻石", 'type'=>1, 'consume_type'=>2, 'resid'=>$momentid, 'create_time'=>nowFormat()]);
        //写入用户消费记录
        $user_profit = new UserProfitModel(['uid'=>$user->id, 'coin_count'=>$moment->unlock_price, 'content'=>"解锁主播({$anchor->nick_name} ID:{$anchor->id})动态(ID:{$moment->id})消费：{$moment->unlock_price}金币", 'type'=>0, 'consume_type'=>2, 'resid'=>$momentid, 'create_time'=>nowFormat()]);

        //增加亲密度
        $intimacy = IntimacyModel::where(['anchorid'=>$anchor->id,'uid'=>$user->id])->find();
        if ($intimacy){
            $intimacy->intimacy = ['inc', $moment->unlock_price];
            $intimacy->intimacy_week = ['inc', $moment->unlock_price];
        }else{
            $intimacy = new IntimacyModel();
            $intimacy->uid = $user->id;
            $intimacy->anchorid = $anchor->id;
            $intimacy->intimacy = $moment->unlock_price;
            $intimacy->intimacy_week = $moment->unlock_price;
        }

        //增加用户经验
        $userpoint = $user->point + addUserPointEventUnlockMoment($moment->unlock_price);
        $user->point = ['inc', addUserPointEventUnlockMoment($moment->unlock_price)];
        //更新用户等级
        $user->user_level = calculateUserLevel($userpoint);

        //增加主播经验
        $anchorpoint = $anchor->anchor_point + $anchor_diamond_get;
        $anchor->anchor_point = ['inc', $anchor_diamond_get];
        $anchor->anchor_level = calculateAnchorLevel($anchorpoint);

        if($anchor->save() && $anchor_profit->save() && $user->save() && $user_profit->save() && $intimacy->save() && $guildup){
            Db::commit();
            return self::bulidSuccess($moment);
        }else{
            Db::rollback();
            return self::bulidFail('解锁失败');
        }
    }

    //点赞
    public function likeMoment(){
        $momentid = Request::param('momentid');
        $moment = MomentModel::get($momentid);
        if (!$moment){
            return self::bulidFail('该动态状态异常');
        }
        $like_count = $moment->like_count;
        $user = $this->userinfo;
        $like = MomentLikeModel::where(['uid'=>$user->id, 'momentid'=>$momentid])->find();
        if ($like){
            return self::bulidSuccess(['like_count'=>$like_count]);
        }
        $like = new MomentLikeModel(['uid'=>$user->id, 'momentid'=>$momentid, 'create_time'=>nowFormat()]);
        $moment->like_count = ['inc', 1];
        $moment->watch_count = ['inc', 1];
        Db::startTrans();
        if ($like->save() && $moment->save()){
            Db::commit();
            $likedids = getUserLikeMomentIds($user->id);
            if ($likedids && !in_array($moment->id,$likedids)){
                $likedids[] = $moment->id;
            }
            setUserLikeMomentIds($user->id, $likedids);
            return self::bulidSuccess(['like_count'=>$like_count + 1]);
        }else{
            Db::rollback();
            return self::bulidFail();
        }
    }

    //收藏、取消收藏
    public function collectMoment(){
        $type = Request::param('type');
        $momentid = Request::param('momentid');
        $moment = MomentModel::get($momentid);
        if (!$moment){
            return self::bulidFail('该动态状态异常，无法收藏');
        }
        if ($moment->uid == $this->userinfo->id){
            return self::bulidFail('无法收藏自己的动态');
        }
        $collect_count = $moment->collect_count;
        $user = $this->userinfo;
        $collect = MomentCollectModel::where(['uid'=>$user->id, 'momentid'=>$momentid])->find();
        if ($collect && $type == 1){
            $collids = getUserCollectMomentIds($user->id);
            if ($collids && !in_array($moment->id,$collids)){
                $collids[] = $moment->id;
            }
            setUserCollectMomentIds($user->id, $collids);
            return self::bulidSuccess(['collect_count'=>$collect_count]);
        }
        if (!$collect && $type == 0){
            $collids = getUserCollectMomentIds($user->id);
            if ($collids && in_array($moment->id, $collids)){
                $collids = array_diff($collids, [$moment->id]);
            }
            setUserCollectMomentIds($user->id, $collids);
            return self::bulidSuccess(['collect_count'=>$collect_count]);
        }

        Db::startTrans();
        if ($type == 0){
            //取消收藏
            $moment->collect_count = ['dec', 1];
            if ($moment->save() && $collect->delete()){
                Db::commit();
                $collids = getUserCollectMomentIds($user->id);
                if ($collids && in_array($moment->id, $collids)){
                    $collids = array_diff($collids, [$moment->id]);
                }
                setUserCollectMomentIds($user->id, $collids);
                return self::bulidSuccess(['collect_count'=>$collect_count - 1]);
            }else{
                Db::rollback();
                return self::bulidFail();
            }
        }elseif ($type == 1){
            $collect = new MomentCollectModel(['uid'=>$user->id, 'momentid'=>$momentid, 'create_time'=>nowFormat()]);
            //收藏
            $moment->collect_count = ['inc', 1];
            if ($moment->save() && $collect->save()){
                Db::commit();
                $collids = getUserCollectMomentIds($user->id);
                if ($collids && !in_array($moment->id,$collids)){
                    $collids[] = $moment->id;
                }
                setUserCollectMomentIds($user->id, $collids);
                return self::bulidSuccess(['collect_count'=>$collect_count + 1]);
            }else{
                Db::rollback();
                return self::bulidFail();
            }
        }
        return self::bulidFail('参数有误');
    }

    public function getComments(){
        $momentid = Request::param('momentid');
        $page = Request::post("page") ?? 1;
        $size = Request::post("size") ?? 10;

        $comments = MomentCommentModel::where(['momentid'=>$momentid, 'rootid'=>0])->with(['user'])->order('create_time','desc')->limit(($page-1)*$size,$size)->select();

        if ($uid = Request::param('uid')){
            $likedids = getUserLikeMomentCommentIds($uid);
            foreach ($likedids as $likedid){
                foreach ($comments as $comment){
                    if ($comment->id == $likedid){
                        $comment->liked = 1;
                    }
                }
            }
        }
        return self::bulidSuccess($comments);
    }

    public function getCommentReplys(){
        $commentid = Request::param('commentid');
        $lastid = Request::post("lastid") ?? 9999999999;
        $size = Request::post("size") ?? 20;

        $comments = MomentCommentModel::where(['rootid'=>$commentid])->where('id','<',$lastid)->with(['user','touser'])->order('id','desc')->limit(0,$size)->select();

        if ($uid = Request::post("uid")) {
            $likedids = getUserLikeMomentCommentIds($uid);
            foreach ($likedids as $likedid) {
                foreach ($comments as $comment) {
                    if ($comment->id == $likedid) {
                        $comment->liked = 1;
                    }
                }
            }
        }
        return self::bulidSuccess($comments);
    }

    public function publishComment(){
        $momentid = Request::param('momentid');
        $touid = Request::param('touid');
        $rootid = Request::param('rootid');
        $tocommentid = Request::param('tocommentid') ?? 0;
        $content = Request::param('content');
        if (!$touid){
            $touid = 0;
        }
        if (!$rootid){
            $rootid = 0;
        }
        $root = MomentCommentModel::get($rootid);
        $moment = MomentModel::where('id',$momentid)->field("id, uid, comment_count")->find();
        if (!$moment){
            return self::bulidFail("动态不存在或状态异常");
        }
        Db::startTrans();
        if ($root){
            $root->reply_count = ['inc', 1];
            $root->save();
        }
        $comment_count = $moment->comment_count;
        $moment->comment_count = ['inc', 1];
        $comment = new MomentCommentModel(['rootid'=>$rootid, 'momentid'=>$momentid, 'uid'=>$this->userinfo->id, 'touid'=>$touid, 'tocommentid'=>$tocommentid, 'content'=>$content, 'create_time'=>nowFormat()]);
        if ($comment->save() && $moment->save()){
            Db::commit();
            $insertComment = MomentCommentModel::where(['id'=>$comment->id])->with(['user','touser'])->find();
            return self::bulidSuccess(['comment'=>$insertComment,'comment_count'=>$comment_count+1]);
        }else{
            Db::rollback();
            return self::bulidFail();
        }
    }

    //评论点赞
    public function likeComment(){
        $commentid = Request::param('commentid');
        $comment = MomentCommentModel::get($commentid);
        if (!$comment){
            return self::bulidFail('该评论状态异常');
        }
        $like_count = $comment->like_count;
        $user = $this->userinfo;
        $like = MomentCommentLikeModel::where(['uid'=>$user->id, 'commentid'=>$commentid])->find();
        if ($like){
            return self::bulidSuccess(['like_count'=>$like_count]);
        }
        $like = new MomentCommentLikeModel(['uid'=>$user->id, 'commentid'=>$commentid, 'create_time'=>nowFormat()]);
        $comment->like_count = ['inc', 1];
        Db::startTrans();
        if ($like->save() && $comment->save()){
            Db::commit();
            $likedids = getUserLikeMomentCommentIds($user->id);
            if ($likedids){
                $likedids[] = $comment->id;
            }
            setUserLikeMomentCommentIds($user->id, $likedids);
            return self::bulidSuccess(['like_count'=>$like_count + 1]);
        }else{
            Db::rollback();
            return self::bulidFail();
        }
    }

    public function getMomentInfo(){
        $id = Request::param('id');
        $moment = MomentModel::where(['id'=>$id])->with('user')->find();
        return self::bulidSuccess(self::handlerData([$moment],Request::param('uid'))[0]);
    }

}