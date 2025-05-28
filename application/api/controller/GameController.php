<?php
namespace app\api\controller;

use app\common\model\AgentModel;
use app\common\model\AgentProfitModel;
use app\common\model\OrderModel;
use app\common\model\UserModel;
use app\common\TXService;
use think\Db;
use think\facade\Env;
use think\facade\Request;

class GameController extends BaseController
{
    // 回调
    public function notify_ae_sexy(){
        $param = Request::param();
    }
}