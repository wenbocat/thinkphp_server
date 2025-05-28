<?php


namespace app\common\model;


use think\facade\Request;
use think\Model;

class AgentWithdrawalsModel extends Model
{
    public function agent(){
        return $this->hasOne('AgentModel','uid','uid')->with('user');
    }
}