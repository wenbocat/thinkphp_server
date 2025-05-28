<?php
/**
 * Created by PhpStorm.
 * User: yun7_mac
 * Date: 2020-01-17
 * Time: 09:44
 */

namespace app\admin\controller;


use app\common\model\ConfigTagModel;
use app\common\model\SystemMsgModel;
use app\common\model\UserModel;
use app\common\TXService;
use think\facade\Request;

class PushController extends BaseController
{
    public function index(){
        return $this->fetch();
    }

    public function getlist(){
        $id = Request::param("id");
        $touid = Request::param("touid");
        $where = [];
        $wherelike = '';
        if ($id){
            $where['id'] = $id;
        }
        if ($touid){
            $wherelike = "touid like %{$touid}% or touid = 0";
        }
        $page = Request::param("page");
        $limit = Request::param("limit");
        $msgs = SystemMsgModel::where($where)->where($wherelike)->limit(($page-1)*$limit,$limit)->order('create_time', 'desc')->select();
        if (count($msgs)>0){
            $count = SystemMsgModel::where($where)->where($wherelike)->count();
            return json(["code"=>0,"msg"=>"","data"=>$msgs,"count"=>$count]);
        }
        return self::bulidFail("未查询到相关数据");
    }

    public function add(){
        return $this->fetch();
    }

    public function add_post(){
        $title = Request::param('title');
        $content = Request::param('content');
        $touid = Request::param('touid');
        $image_url = Request::param('image_url');
        $link = Request::param('link');
        if (strlen($title) > 50){
            return self::bulidFail('标题最多50字');
        }
        if (strlen($content) > 200){
            return self::bulidFail('内容最多200字');
        }
        $uidArr = [];
        if (!$touid){
            $touid = 0;
        }else{
            $uidArr = explode(',', $touid);
        }
        $stmmsgModel = new SystemMsgModel(['touid'=>$touid, 'title'=>$title, 'content'=>$content, 'image_url'=>$image_url, 'link'=>$link, 'create_time'=>nowFormat()]);
        if (!$stmmsgModel->save()){
            return self::bulidFail();
        }

        //调用IM推送
        if (count($uidArr) == 0){
            $users = UserModel::field('id')->select()->toArray();
            $uidArr = explode(',',implode(',',array_column($users,'id')));
        }
        $result = [];
        for ($i = 0; $i<count($uidArr);){
            $pushids = array_slice($uidArr, $i, 500);
            $i += 500;
            $result[] = TXService::sendSystemMsg($pushids,TXService::buildCustomElem('SystemMessage',[],'系统消息',$title));
        }
        return self::bulidSuccess($result);
    }
}