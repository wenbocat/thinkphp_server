<?php


namespace app\api\controller;


use app\common\model\SmscodeModel;
use app\common\model\UserAuthModel;
use app\common\model\AnchorWithdrawalsModel;
use app\common\model\UserWithdrawAccountModel;
use think\Db;
use think\facade\Request;

class WithdrawController extends BaseController
{
    protected $NeedLogin = ['withdraw','withdrawlog','editCashAccount','getAccount'];

    protected $rules = array(
        'withdraw'=>[
            'diamond'=>'require',
            'cash'=>'require',
            'alipay_account'=>'require',
            'alipay_name'=>'require',
        ],
        'editcashaccount'=>[
            'alipay_account'=>'require',
            'alipay_name'=>'require',
            'smscode'=>'require',
        ]
    );

    public function initialize()
    {
        parent::initialize();
    }

    public function withdraw(){
        $withdraw = new AnchorWithdrawalsModel(Request::post());
        $withdraw->create_time = nowFormat();

        if ($this->userinfo->diamond < $withdraw->diamond){
            return self::bulidFail('钻石不足，提现失败');
        }

        $configPri = getConfigPri();
        if (floatval($withdraw->cash) > $withdraw->diamond / $configPri->exchange_rate + 1){
            return self::bulidFail('数据异常，提现失败');
        }

        $this->userinfo->diamond = ['dec',$withdraw->diamond];

        Db::startTrans();
        if ($withdraw->save() && $this->userinfo->save()){
            Db::commit();
            return self::bulidSuccess([],'工作人员将在24小时内处理您的提现申请');
        }else{
            Db::rollback();
            return self::bulidFail();
        }
    }

    public function withdrawlog(){
        $page = Request::post("page") ?? 1;
        $size = Request::post("size") ?? 20;
        $logs = AnchorWithdrawalsModel::where(['uid'=>$this->userinfo->id])->order('create_time','desc')->limit(($page-1)*$size,$size)->select();
        return self::bulidSuccess($logs);
    }

    public function editCashAccount(){
        $code = Request::post('smscode');
        //验证码
        $codeinfo = SmscodeModel::where("mobile",$this->userinfo->account)->where("code",$code)->where("status",0)->whereTime("create_time","-30 minutes")->order("create_time","desc")->find();
        if (!$codeinfo){
            return self::bulidFail("验证码有误或已过期");
        }
        $codeinfo->status = 1;
        $codeinfo->save();

        $alipay_account = Request::post('alipay_account');
        $alipay_name = Request::post('alipay_name');

        if ($accountid = Request::param('accountid')){
            $account = UserWithdrawAccountModel::where(['id'=>$accountid,'uid'=>$this->userinfo->id])->find();
            if ($account){
                if($account->save(['alipay_account'=>$alipay_account,'alipay_name'=>$alipay_name])){
                    return self::bulidSuccess();
                }
            }
        }else{
            $account = new UserWithdrawAccountModel(['uid'=>$this->userinfo->id,'alipay_account'=>$alipay_account,'alipay_name'=>$alipay_name]);
            if($account->save()){
                return self::bulidSuccess();
            }
        }
        return self::bulidFail();
    }

    public function getAccount(){
        $account = UserWithdrawAccountModel::where('uid',$this->userinfo->id)->find();
        return self::bulidSuccess($account);
    }
}