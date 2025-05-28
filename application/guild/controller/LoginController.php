<?php
/**
 * Created by PhpStorm.
 * User: yun7_mac
 * Date: 2019-11-05
 * Time: 15:53
 */

namespace app\guild\controller;

use app\common\model\GuildModel;
use think\facade\Cookie;
use think\facade\Request;

class LoginController extends BaseController
{
    public function initialize()
    {
    }

    public function index(){
        $token = Cookie::get("guildToken");
        $guildid = Cookie::get("guildid");
        $this->userinfo = GuildModel::where("id",$guildid)->where("token",$token)->find();
        if ($this->userinfo){
            $this->redirect("/guild/Index/index");
        }
        $this->assign('config_pub',getConfigPub());
        return $this->fetch();
    }

    public function editpwd(){
        return $this->fetch();
    }

    public function login(){
        $mobile = Request::param("mobile");
        $pwd = openssl_encrypt(substr($mobile,strlen($mobile)-4,4).Request::param("password"),"DES-ECB",$this->OPENSSL_KEY);
        $vercode = Request::param("vercode");
        if (!captcha_check($vercode)){
            return self::bulidFail("验证码有误");
        }
        $this->userinfo = GuildModel::where("mobile",$mobile)->where("password",$pwd)->find();
        if ($this->userinfo){
            $this->userinfo->token = strtoupper(openssl_encrypt(date("YmdHis").rand(10000,99999),"DES-ECB",$this->OPENSSL_KEY));
            $this->userinfo->save();
            Cookie::set("guildid",$this->userinfo->id);
            Cookie::set("guildToken",$this->userinfo->token);
            return self::bulidSuccess($this->userinfo,"登录成功");
        }else{
            return self::bulidFail("用户名或密码错误");
        }
    }

    public function logout(){
        Cookie::delete("guildid");
        Cookie::delete("token");
        return self::bulidSuccess([],"登出成功");
    }

    public function editpwd_post(){
        $guildid = Cookie::get("guildid");
        if (!$guildid){
            $this->redirect("/guild/Login/redirectpage");
            exit();
        }
        $guild = GuildModel::where(['id'=>$guildid])->find();
        $oldpwd = openssl_encrypt(substr($guild->mobile,strlen($guild->mobile)-4,4).Request::param("oldpwd"),"DES-ECB",$this->OPENSSL_KEY);
        if ($oldpwd != $guild->password){
            return self::bulidFail('旧密码验证失败');
        }
        $guild->password = openssl_encrypt(substr($guild->mobile,strlen($guild->mobile)-4,4).Request::param("newpwd"),"DES-ECB",$this->OPENSSL_KEY);
        if ($guild->save()){
            return self::bulidSuccess();
        }
        return self::bulidFail();
    }

    public function redirectpage(){
        return $this->fetch();
    }
}