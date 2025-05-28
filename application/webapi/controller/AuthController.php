<?php


namespace app\webapi\controller;


use app\common\model\UserAuthModel;
use app\common\model\UserModel;
use think\Db;
use think\facade\Request;

class AuthController extends BaseController
{
    protected $NeedLogin = ['identityAuth','youliaoAuth'];

    protected $rules = array(
        'identityauth'=>[
            'real_name'=>'require',
            'id_num'=>'require',
            'id_card_url'=>'require',
        ],
    );

    public function initialize()
    {
        parent::initialize();
    }

    public function identityAuth(){
        $newauth = new UserAuthModel(Request::post());
        $newauth->submit_time = nowFormat();
        $newauth->status = 0;

        $auth = UserAuthModel::where(['uid'=>$this->userinfo->id])->find();
        if ($auth){
            if ($auth->status == 1){
                return self::bulidSuccess();
            }
            Db::startTrans();
            if ($auth->delete() && $newauth->save()){
                Db::commit();
                return self::bulidSuccess();
            }else{
                Db::rollback();
                return self::bulidFail();
            }
        }else{
            if ($newauth->save()){
                return self::bulidSuccess();
            }
        }
        return self::bulidFail();
    }

    public function sendBindCode(){
        $mobile = Request::param("mobile");

        //查询手机号是否被占用
        $user = UserModel::where(['account'=>$mobile])->find();
        if ($user){
            return self::bulidFail('手机号已被占用');
        }

        $ip = Request::ip();
        //查询最近5分钟内是否发送过
        $codeInfo1 = Db::table("db_smscode")->where("mobile",$mobile)->whereTime("create_time","-1 minutes")->where("status",0)->select();
        if (count($codeInfo1)>0){
            return self::bulidFail("验证码发送过于频繁，请稍后再试");
        }
        $codeInfo2 = Db::table("db_smscode")->where("request_ip",$ip)->whereTime("create_time","-1 minutes")->where("status",0)->select();
        if (count($codeInfo2)>0){
            return self::bulidFail("验证码发送过于频繁，请稍后再试");
        }

        $code = rand(100000,999999);

        if (sendCode($mobile,$code,$ip)){
            return self::bulidSuccess();
        }else{
            return self::bulidFail('发送失败');
        }
    }
}