<?php
/**
 * Created by PhpStorm.
 * User: yun7_mac
 * Date: 2019-11-05
 * Time: 15:53
 */

namespace app\admin\controller;


use app\common\model\AdminModel;
use think\facade\Cookie;
use think\facade\Request;

class LoginController extends BaseController
{
    public function initialize()
    {
    }

    public function index(){
        $token = Cookie::get("token");
        $uid = Cookie::get("uid");
        $this->userinfo = AdminModel::where("id",$uid)->where("token",$token)->find();
        if ($this->userinfo){
            $this->redirect("/admin/Index/index");
        }
        $this->assign('config_pub',getConfigPub());
        return $this->fetch();
    }

    public function editpwd(){
        return $this->fetch();
    }

    public function login(){
        $account = Request::param("account");
        $pwd = openssl_encrypt($account.Request::param("password"),"DES-ECB",$this->OPENSSL_KEY);
        $vercode = Request::param("vercode");
        if (!captcha_check($vercode)){
            return self::bulidFail("验证码有误");
        }
        $this->userinfo = AdminModel::where("account",$account)->where("password",$pwd)->find();
                if ($this->userinfo){
            $this->userinfo->token = strtoupper(openssl_encrypt(date("YmdHis").rand(10000,99999),"DES-ECB",$this->OPENSSL_KEY));
            $this->userinfo->save();
            Cookie::set("uid",$this->userinfo->id);
            Cookie::set("token",$this->userinfo->token);
            return self::bulidSuccess($this->userinfo,"登录成功");
        }else{
            return self::bulidDataFail(['p'=>$pwd], "用户名或密码错误");
        }
    }

    public function logout(){
        Cookie::delete("uid");
        Cookie::delete("token");
        return self::bulidSuccess([],"登出成功");
    }

    public function editpwd_post(){
        $uid = Cookie::get("uid");
        if (!$uid){
            $this->redirect("/admin/Login/redirectpage");
            exit();
        }
        $user = AdminModel::where(['id'=>$uid])->find();
        $oldpwd = openssl_encrypt($user->account.Request::param("oldpwd"),"DES-ECB",$this->OPENSSL_KEY);
        if ($oldpwd != $user->password){
            return self::bulidFail('旧密码验证失败');
        }
        $user->password = openssl_encrypt($user->account.Request::param("newpwd"),"DES-ECB",$this->OPENSSL_KEY);
        if ($user->save()){
            return self::bulidSuccess();
        }
        return self::bulidFail();
    }

    public function redirectpage(){
        return $this->fetch();
    }
}