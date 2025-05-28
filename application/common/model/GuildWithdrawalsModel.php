<?php
/**
 * Created by PhpStorm.
 * User: yun7_mac
 * Date: 2019-11-30
 * Time: 23:05
 * Info: 主播提现申请模型
 */

namespace app\common\model;


use think\Model;

class GuildWithdrawalsModel extends Model
{
    public function guild(){
        return $this->hasOne(GuildModel::class,'id','guildid');
    }
}