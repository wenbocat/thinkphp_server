<?php


namespace app\api\controller;


use app\common\model\MomentModel;
use app\common\model\ShortvideoCollectModel;
use app\common\model\ShortvideoCommentLikeModel;
use app\common\model\ShortvideoCommentModel;
use app\common\model\ShortvideoLikeModel;
use app\common\model\ShortvideoModel;
use app\common\model\ShortvideoReportModel;
use app\common\model\TopicModel;
use think\Db;
use think\facade\Request;

class ShortvideoController extends BaseController
{
    protected $NeedLogin = ['likeVideo', 'publish','setComment','likeComment','getCollection','collect','addShareCount','report'];

    protected $rules = array(
        //点赞视频
        'likevideo'=>[
            'videoid'=>'require',
            'type'=>'require' //1-点赞 0-取消点赞
        ],
        //评论列表
        'getcomments'=>[
            'videoid'=>'require'
        ],
        //评论回复列表
        'getcommentreplys'=>[
            'commentid'=>'require'
        ],
        //发表评论
        'setcomment'=>[
            'videoid'=>'require',
            'content'=>'require'
        ],
        //点赞评论
        'likecomment'=>[
            'commentid'=>'require'
        ],
        //短视频用户信息
        'getuserinfo'=>[
            'authorid'=>'require',
        ],
        //获取用户发布的短视频
        'getlistbyuser'=>[
            'userid'=>'require',
        ],
        //获取用户喜欢的短视频
        'getlistuserlike'=>[
            'userid'=>'require',
        ],
        //收藏
        'collect'=>[
            'videoid'=>'require',
            'type'=>'require' //1-收藏 0-取消收藏
        ],
        //增加分享数量
        'addsharecount'=>[
            'videoid'=>'require',
        ],
        //举报
        'report'=>[
            'relateid'=>'require',
            'title'=>'require',
            'content'=>'require',
            'img_urls'=>'require'
        ],
        //搜索
        'search'=>[
            'keyword'=>'require'
        ],
        //话题下的短视频
        'listintopic'=>[
            'topic'=>'require',
        ]
    );

    public function getRandomList(){

        $page = Request::param('page') ?? 1;
        $size = Request::param('size') ?? 20;

        $count = ShortvideoModel::count('id');
        if ($count < 50){
            $list = ShortvideoModel::where(['status'=>1])->with(['author'])->order('create_time','desc')->limit($size*($page-1),$size)->select();
        }else{
            $list = ShortvideoModel::where(['status'=>1])->with(['author'])->orderRand()->limit($size*($page-1),$size)->select();
        }

        return self::bulidSuccess(self::handlerData($list,Request::post("uid")));
    }

    public function getHotList(){

        $page = Request::param('page') ?? 1;
        $size = Request::param('size') ?? 20;

        $list = ShortvideoModel::where(['status'=>1])->with(['author'])->order('like_count','desc')->limit($size*($page-1),$size)->select();

        return self::bulidSuccess(self::handlerData($list,Request::post("uid")));
    }

    public function getListByUser(){
        $page = Request::param('page') ?? 1;
        $size = Request::param('size') ?? 20;
        $authorid = Request::param('userid');

        $list = ShortvideoModel::where(['uid'=>$authorid,'status'=>1])->with(['author'])->order('create_time','desc')->limit($size*($page-1),$size)->select();
        return self::bulidSuccess(self::handlerData($list,Request::post("uid")));
    }

    public function getListUserLike(){
        $page = Request::param('page') ?? 1;
        $size = Request::param('size') ?? 20;
        $userid = Request::param('userid');

        $list = ShortvideoModel::where(['status'=>1])->where('id','in',getUserLikeShortVideoIds($userid))->with(['author'])->order('create_time','desc')->limit($size*($page-1),$size)->select();

        return self::bulidSuccess(self::handlerData($list,Request::post("uid")));
    }

    public function search(){
        $page = Request::post("page") ?? 1;
        $size = Request::post("size") ?? 20;
        $keyword = Request::param('keyword');
        $whereRaw = "title like '%{$keyword}%'";
        $list = ShortvideoModel::where(['status'=>1])->whereRaw($whereRaw)->with(['author'])->order('create_time','desc')->limit($size*($page-1),$size)->select();
        return self::bulidSuccess(self::handlerData($list,Request::post("uid")));
    }

    public function listInTopic(){
        $topic = Request::param('topic');
        $type = Request::param('type') ?? 0;
        $page = Request::post("page") ?? 1;
        $size = Request::post("size") ?? 20;

        $order = ['create_time'=>'desc'];
        if ($type == 0){
            $order = ['like_count'=>'desc','create_time'=>'desc'];
        }

        $moments = ShortvideoModel::where(['status'=>1, 'topic'=>$topic])->with(['author'])->order($order)->limit(($page-1)*$size,$size)->select();

        return self::bulidSuccess(self::handlerData($moments,Request::param('uid')));
    }

    static private function handlerData($videos,$uid){
        if (!$uid){
            return $videos;
        }
        $attentids = getUserAttentAnchorIds($uid);
        foreach ($attentids as $attentid){
            foreach ($videos as $index=>$video){
                if ($video->author->id == $attentid){
                    $video->author->isattent = 1;
                }
            }
        }
        $likedids = getUserLikeShortVideoIds($uid);
        foreach ($likedids as $likedid){
            foreach ($videos as $index=>$video){
                if ($video->id == $likedid){
                    $video->is_like = 1;
                }
            }
        }
        $collids = getUserCollectShortVideoIds($uid);
        foreach ($collids as $collid) {
            foreach ($videos as $video) {
                if ($video->id == $collid) {
                    $video->collected = 1;
                }
            }
        }
        return $videos;
    }

    public function likeVideo(){
        $videoid = Request::param('videoid');
        $type = Request::param('type');
        $video = ShortvideoModel::where(['id'=>$videoid])->find();
        if (!$video){
            return self::bulidFail('该视频状态异常');
        }
        $like_count = $video->like_count;
        $user = $this->userinfo;
        $like = ShortvideoLikeModel::where(['uid'=>$user->id, 'videoid'=>$videoid])->find();
        if ($like && $type == 1){
            $likedids = getUserLikeShortVideoIds($user->id);
            if ($likedids && !in_array($video->id,$likedids)){
                $likedids[] = $video->id;
            }
            setUserLikeShortVideoIds($user->id, $likedids);
            return self::bulidSuccess(['like_count'=>$like_count,'ids'=>$likedids]);
        }
        if (!$like && $type == 0){
            $likedids = getUserLikeShortVideoIds($user->id);
            if ($likedids && in_array($videoid, $likedids)){
                $likedids = array_diff($likedids, [$videoid]);
            }
            setUserLikeShortVideoIds($user->id, $likedids);
            return self::bulidSuccess(['like_count'=>$like_count,'ids'=>$likedids]);
        }
        if ($type == 0){
            $video->like_count = ['dec', 1];
            Db::startTrans();
            if ($like->delete() && $video->save()) {
                Db::commit();
                $likedids = getUserLikeShortVideoIds($user->id);
                if ($likedids && in_array($videoid, $likedids)){
                    $likedids = array_diff($likedids, [$videoid]);
                }
                setUserLikeShortVideoIds($user->id, $likedids);
                return self::bulidSuccess(['like_count'=>$like_count-1,'ids'=>$likedids]);
            }else{
                Db::rollback();
                return self::bulidFail();
            }
        }elseif ($type == 1){
            $like = new ShortvideoLikeModel(['uid'=>$user->id, 'videoid'=>$videoid, 'create_time'=>nowFormat()]);
            $video->like_count = ['inc', 1];
            $video->watch_count = ['inc', 1];
            Db::startTrans();
            if ($like->save() && $video->save()){
                Db::commit();
                $likedids = getUserLikeShortVideoIds($user->id);
                if ($likedids && !in_array($video->id,$likedids)){
                    $likedids[] = $video->id;
                }
                setUserLikeShortVideoIds($user->id, $likedids);
                return self::bulidSuccess(['like_count'=>$like_count + 1,'ids'=>$likedids]);
            }else{
                Db::rollback();
                return self::bulidFail();
            }
        }
        return self::bulidFail();
    }

    //收藏、取消收藏
    public function collect(){
        $type = Request::param('type');
        $videoid = Request::param('videoid');
        $video = ShortvideoModel::where(['id'=>$videoid])->find();
        if (!$video){
            return self::bulidFail('该视频状态异常，无法收藏');
        }
        if ($video->uid == $this->userinfo->id){
            return self::bulidFail('无法收藏自己的视频');
        }
        $collect_count = $video->collect_count;
        $user = $this->userinfo;
        $collect = ShortvideoCollectModel::where(['uid'=>$user->id, 'videoid'=>$videoid])->find();
        if ($collect && $type == 1){
            $collids = getUserCollectShortVideoids($user->id);
            if ($collids && !in_array($video->id,$collids)){
                $collids[] = $video->id;
            }
            setUserCollectShortVideoids($user->id, $collids);
            return self::bulidSuccess(['collect_count'=>$collect_count]);
        }
        if (!$collect && $type == 0){
            $collids = getUserCollectShortVideoids($user->id);
            if ($collids && in_array($video->id, $collids)){
                $collids = array_diff($collids, [$video->id]);
            }
            setUserCollectShortVideoids($user->id, $collids);
            return self::bulidSuccess(['collect_count'=>$collect_count]);
        }

        Db::startTrans();
        if ($type == 0){
            //取消收藏
            $video->collect_count = ['dec', 1];
            if ($video->save() && $collect->delete()){
                Db::commit();
                $collids = getUserCollectShortVideoids($user->id);
                if ($collids && in_array($video->id, $collids)){
                    $collids = array_diff($collids, [$video->id]);
                }
                setUserCollectShortVideoids($user->id, $collids);
                return self::bulidSuccess(['collect_count'=>$collect_count - 1]);
            }else{
                Db::rollback();
                return self::bulidFail();
            }
        }elseif ($type == 1){
            $collect = new ShortvideoCollectModel(['uid'=>$user->id, 'videoid'=>$videoid, 'create_time'=>nowFormat()]);
            //收藏
            $video->collect_count = ['inc', 1];
            if ($video->save() && $collect->save()){
                Db::commit();
                $collids = getUserCollectShortVideoids($user->id);
                if ($collids && !in_array($video->id,$collids)){
                    $collids[] = $video->id;
                }
                setUserCollectShortVideoids($user->id, $collids);
                return self::bulidSuccess(['collect_count'=>$collect_count + 1]);
            }else{
                Db::rollback();
                return self::bulidFail();
            }
        }
        return self::bulidFail('参数有误');
    }

    public function publish(){
        $config_pri = getConfigPri();
        $video = new ShortvideoModel(Request::param());
        if ($config_pri->switch_shortvideo_check){
            $video->status = 0;
        }else{
            $video->status = 1;
            //增加用户经验
            $user = $this->userinfo;
            $userpoint = $user->point + addUserPointEventPublishShortVideo();
            $user->point = ['inc', addUserPointEventPublishShortVideo()];
            //更新用户等级
            $user->user_level = calculateUserLevel($userpoint);
            $user->save();
        }
        $video->create_time = nowFormat();
        if ($video->save()){
            $msg = $config_pri->switch_shortvideo_check ? "发布成功，请等待管理员审核":"发布成功";
            if ($topic = Request::param('topic')){
                //话题参与人次+1
                TopicModel::where(['title'=>str_replace('#','',$topic)])->update(['used_times'=>['inc',1]]);
            }
            return self::bulidSuccess(['st'=>0],$msg);
        }else{
            return self::bulidFail();
        }
    }


    public function getComments(){
        $videoid = Request::param('videoid');
        $lastid = Request::post("lastid") ?? 9999999999;
        $size = Request::post("size") ?? 20;

        $comments = ShortvideoCommentModel::where(['videoid'=>$videoid, 'rootid'=>0])->where('id','<',$lastid)->with(['user'])->order('id','desc')->limit(0,$size)->select();

        if ($uid = Request::post("uid")) {
            $likedids = getUserLikeShortVideoCommentIds($uid);
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

    public function getCommentReplys(){
        $commentid = Request::param('commentid');
        $lastid = Request::post("lastid") ?? 9999999999;
        $size = Request::post("size") ?? 20;

        $comments = ShortvideoCommentModel::where(['rootid'=>$commentid])->where('id','<',$lastid)->with(['user','touser'])->order('id','desc')->limit(0,$size)->select();

        if ($uid = Request::post("uid")) {
            $likedids = getUserLikeShortVideoCommentIds($uid);
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

    public function setComment(){
        $videoid = Request::param('videoid');
        $tocommentid = Request::param('tocommentid') ?? 0;
        $rootid = Request::param('rootid') ?? 0;
        $touid = Request::param('touid') ?? 0;
        $content = Request::param('content');

        $root = ShortvideoCommentModel::get($rootid);
        $video = ShortvideoModel::where(['id'=>$videoid])->find();
        if (!$video){
            return self::bulidFail('参数有误');
        }

        $video->comment_count = ['inc',1];

        $comment = new ShortvideoCommentModel(['uid'=>$this->userinfo->id,'videoid'=>$videoid,'rootid'=>$rootid,'tocommentid'=>$tocommentid,'content'=>$content,'touid'=>$touid,'create_time'=>nowFormat()]);

        Db::startTrans();
        if ($root){
            $root->reply_count = ['inc', 1];
            $root->save();
        }
        if ($comment->save() && $video->save()){
            Db::commit();
            $insertComment = ShortvideoCommentModel::where(['id'=>$comment->id])->with(['user','touser'])->find();
            return self::bulidSuccess($insertComment);
        }else{
            Db::rollback();
            return self::bulidFail();
        }
    }

    //评论点赞
    public function likeComment(){
        $commentid = Request::param('commentid');
        $comment = ShortvideoCommentModel::get($commentid);
        if (!$comment){
            return self::bulidFail('该评论状态异常');
        }
        $like_count = $comment->like_count;
        $user = $this->userinfo;
        $like = ShortvideoCommentLikeModel::where(['uid'=>$user->id, 'commentid'=>$commentid])->find();
        if ($like){
            $likedids = getUserLikeShortVideoCommentIds($user->id);
            if ($likedids && !in_array($commentid,$likedids) ){
                $likedids[] = $commentid;
            }
            return self::bulidSuccess(['like_count'=>$like_count]);
        }
        $like = new ShortvideoCommentLikeModel(['uid'=>$user->id, 'commentid'=>$commentid, 'create_time'=>nowFormat()]);
        $comment->like_count = ['inc', 1];
        Db::startTrans();
        if ($like->save() && $comment->save()){
            Db::commit();
            $likedids = getUserLikeShortVideoCommentIds($user->id);
            if ($likedids && !in_array($commentid,$likedids) ){
                $likedids[] = $commentid;
            }
            setUserLikeShortVideoCommentIds($user->id, $likedids);
            return self::bulidSuccess(['like_count'=>$like_count + 1]);
        }else{
            Db::rollback();
            return self::bulidFail();
        }
    }

    public function getUserInfo(){
        $authorid = Request::post('authorid');
        $starCount = ShortvideoModel::where(['uid'=>$authorid,'status'=>1])->sum('like_count');
        $attentCount = count(getUserAttentAnchorIds($authorid));
        $fansCount = getFansCount($authorid);

        $videoCount = ShortvideoModel::where(['uid'=>$authorid,'status'=>1])->count('id');
        $momentCount = MomentModel::where(['uid'=>$authorid,'status'=>1])->count('id');
        $likeVideoCount = count(getUserLikeShortVideoIds($authorid));

        return self::bulidSuccess(['star_count'=>$starCount,'attent_count'=>$attentCount,'fans_count'=>$fansCount,'video_count'=>$videoCount,'moment_count'=>$momentCount,'like_video_count'=>$likeVideoCount]);
    }

    public function getCollection(){
        $page = Request::param('page') ?? 1;
        $size = Request::param('size') ?? 20;

        $collects = ShortvideoCollectModel::where(['uid'=>$this->userinfo->id])->with('video')->order('create_time','desc')->limit($size*($page-1),$size)->select();
        $list = [];
        foreach ($collects as $collect){
            $collect->video->collected = 1;
            $list[] = $collect->video;
        }

        $attentids = getUserAttentAnchorIds($this->userinfo->id);
        foreach ($attentids as $attentid){
            foreach ($list as $index=>$video){
                if ($video->author->id == $attentid){
                    $video->author->isattent = 1;
                }
            }
        }
        $likedids = getUserLikeShortVideoIds($this->userinfo->id);
        foreach ($likedids as $likedid){
            foreach ($list as $index=>$video){
                if ($video->id == $likedid){
                    $video->is_like = 1;
                }
            }
        }

        return self::bulidSuccess($list);
    }

    public function addShareCount(){
        if (ShortvideoModel::where(['id'=>Request::param('videoid')])->update(['share_count'=>['inc',1]])){
            return self::bulidSuccess();
        }
        return self::bulidFail();
    }

    public function reoprt(){
        $model = new ShortvideoReportModel(Request::param());
        $model->videoid = Request::param('relateid');
        $model->create_time = nowFormat();
        if ($model->save()){
            return self::bulidSuccess();
        }
        return self::bulidFail();
    }
}