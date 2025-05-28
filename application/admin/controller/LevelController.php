<?php
/**
 * Created by PhpStorm.
 * User: yun7_mac
 * Date: 2019-12-06
 * Time: 10:38
 */

namespace app\admin\controller;


use app\common\model\UserLevelRuleModel;
use app\common\model\AnchorLevelRuleModel;
use think\facade\Request;

class LevelController extends BaseController
{
    public function pointlevel(){
        return $this->fetch();
    }
    public function starlevel(){
        return $this->fetch();
    }

    public function pointlevel_add(){
        return $this->fetch();
    }

    public function starlevel_edit(){
        $starlevelinfo = AnchorLevelRuleModel::get(Request::param('id'));
        $this->assign("starlevelinfo",$starlevelinfo);
        return $this->fetch();
    }

    public function getPointLevel(){
        $levels = UserLevelRuleModel::order("level","asc")->all();
        return self::bulidSuccess($levels);
    }

    public function pointlevel_add_post(){
        $level = new UserLevelRuleModel(Request::param());
        if ($level->save()){
            return self::bulidSuccess([]);
        }
        return self::bulidFail();
    }

    public function pointlevel_edit_post(){
        $level = UserLevelRuleModel::get(Request::param("id"));
        if (!$level){
            return self::bulidFail("等级规则条目不存在");
        }
        $newpoint = Request::param("point");
        $level_lower = UserLevelRuleModel::get(["level"=>$level->level-1]);
        if ($level_lower && $level_lower->point >= $newpoint){
            return self::bulidFail("高等级所需经验值不得低于低等级");
        }
        if ($level->save(["point"=>$newpoint])){
            return self::bulidSuccess([]);
        }
        return self::bulidFail();
    }

    public function pointlevel_del_post(){
        if (UserLevelRuleModel::destroy(Request::param('id'))){
            return self::bulidSuccess([]);
        }else{
            return self::bulidFail();
        }
    }

    public function getStarLevel(){
        $levels = AnchorLevelRuleModel::order("level","asc")->all();
        return self::bulidSuccess($levels);
    }

    public function starlevel_edit_post(){
        $level = AnchorLevelRuleModel::get(Request::param("id"));
        if (!$level){
            return self::bulidFail("等级规则条目不存在");
        }

        if ($level->save(Request::param())){
            return self::bulidSuccess([]);
        }
        return self::bulidFail();
    }
}