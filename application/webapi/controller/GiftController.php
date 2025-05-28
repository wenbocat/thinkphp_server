<?php


namespace app\webapi\controller;


use app\common\model\GiftLogModel;
use app\common\model\GiftModel;
use app\common\model\IntimacyModel;
use app\common\model\LiveModel;
use app\common\model\UserConsumeModel;
use app\common\model\UserModel;
use app\common\model\UserProfitModel;
use app\common\TXService;
use think\Db;
use think\facade\Request;

class GiftController extends BaseController
{
    protected $NeedLogin = ['sendGift'];

    protected $rules = array(
        'sendgift'=>[
            'anchorid'=>'require',
            'giftid'=>'require'
        ],
    );

    public function initialize()
    {
        parent::initialize();
    }

    public function getGiftList(){
        $gifts = GiftModel::where("status", '1')->order(['sort'=>'asc', 'id'=>'asc'])->select();
        return self::bulidSuccess($gifts);
    }

    public function sendGift(){
        $giftid = Request::param('giftid');
        $anchorid = Request::param('anchorid');
        $liveid = Request::param('liveid') ?? 0;
        $count = Request::param('count') ?? 1;

        if ($anchorid == $this->userinfo->id){
            return self::bulidFail("无法给自己赠送礼物");
        }

        $gift = GiftModel::where(['id'=>$giftid])->find();
        if (!$gift){
            return self::bulidFail('礼物不存在');
        }
        $gold_cost = $gift->price * $count;
        if ($gold_cost  > $this->userinfo->gold){
            return self::bulidChargeFail();
        }

        $usergoldleft = $this->userinfo->gold - $gold_cost;

        $sendlogModel = new GiftLogModel(['uid'=>$this->userinfo->id,'anchorid'=>$anchorid,'liveid'=>$liveid,'create_time'=>nowFormat(),'spend'=>$gold_cost, 'giftid'=>$giftid, 'count'=>$count]);

        Db::startTrans();

        if ($sendlogModel->save()){
//            //金币钻石结算
            $user = $this->userinfo;
            $anchor = UserModel::where(['id'=>$anchorid])->field(['id,nick_name','online_status','sharing_ratio','guildid','anchor_point'])->find();

            //扣除用户金币
            $user->gold = ['dec', $gold_cost];
            //增加用户经验值
            $userpoint = $user->point + addUserPointEventSendGift($gold_cost);
            $user->point = ['inc', addUserPointEventSendGift($gold_cost)];
            //更新用户等级
            $user->user_level = calculateUserLevel($userpoint);

            //增加主播钻石
            $anchor_diamond_get = round($gold_cost * $anchor->sharing_ratio / 100);
            $anchor->diamond = ['inc', $anchor_diamond_get];
            $anchor->diamond_total = ['inc', $anchor_diamond_get];
            //增加主播经验值
            $anchorpoint = $anchor->anchor_point + $anchor_diamond_get;
            $anchor->anchor_point = ['inc',$anchor_diamond_get];
            //更新主播等级
            $anchor->anchor_level = calculateUserLevel($anchorpoint);

            //写入主播收益记录
            $anchor_profit = new UserProfitModel(['uid'=>$anchor->id, 'coin_count'=>$anchor_diamond_get, 'content'=>"收到礼物{$gift->title}(ID:{$giftid})x{$count} 收益：{$anchor_diamond_get}钻石", 'type'=>1, 'consume_type'=>1, 'resid'=>$giftid,'create_time'=>nowFormat()]);
            //写入用户消费记录
            $user_profit = new UserProfitModel(['uid'=>$user->id, 'coin_count'=>$gold_cost, 'content'=>"赠送主播({$anchor->nick_name} ID:{$anchor->id})礼物{$gift->title}(ID:{$giftid})x{$count} 消费：{$gold_cost}金币", 'type'=>0, 'consume_type'=>1, 'resid'=>$giftid, 'create_time'=>nowFormat()]);

            //增加亲密度
            $intimacy = IntimacyModel::where(['anchorid'=>$anchor->id,'uid'=>$user->id])->find();
            if ($intimacy){
                $intimacy->intimacy = ['inc', $gold_cost];
                $intimacy->intimacy_week = ['inc', $gold_cost];
            }else{
                $intimacy = new IntimacyModel();
                $intimacy->uid = $user->id;
                $intimacy->anchorid = $anchor->id;
                $intimacy->intimacy = $gold_cost;
                $intimacy->intimacy_week = $gold_cost;
            }

            //写入用户当日消费统计 用于排行榜
            $userConsume = UserConsumeModel::where(['uid'=>$user->id])->whereTime('date','today')->find();
            if (!$userConsume){
                $userConsume = new UserConsumeModel(['uid'=>$user->id,'consume'=>0,'date'=>date('Y-m-d')]);
            }
            $userConsume->consume = ['inc',$gold_cost];

            //写入直播收益
            $live_profit_update = 1;
            if ($liveid){
                $live_profit_update = Db::table('db_live')->where('liveid',$liveid)->inc('profit',$gold_cost)->update();
            }

            if($anchor->save() && $anchor_profit->save() && $user->save() && $user_profit->save() && $intimacy->save() && $userConsume->save() && $live_profit_update){
                Db::commit();
                self::echoSuccess(['gold'=>$usergoldleft]);
                //增加直播热度
                if ($liveid){
                    LiveModel::update(['hot'=>['inc',$gold_cost*100]],['liveid'=>$liveid]);
                    //通过IM推送到直播间
                    $gift->sender = $user->visible(['id','nick_name','user_level','avatar']);
                    $gift->count = $count;
                    $im_ext['gift'] = $gift;
                    $elem = TXService::buildCustomElem('GiftAnimation',$im_ext);
                    TXService::sendChatRoomMsg(getAnchorRoomID($anchorid),$elem);
                }
                exit();
            }else{
                Db::rollback();
                return self::bulidCodeFail(1001,"失败");
            }
        }else{
            Db::rollback();
            return self::bulidFail();
        }
    }
}