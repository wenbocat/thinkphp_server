<?php
/**
 * Created by PhpStorm.
 * User: yun7_mac
 * Date: 2019-12-20
 * Time: 11:06
 */

namespace app\common\model;


use think\Model;

class GuildProfitModel extends Model
{
    public function guild(){
        return $this->hasOne(GuildModel::class,'id','guildid');
    }
}