<?php


namespace app\index\controller;


use think\Controller;

class IndexController extends Controller
{
    public function index(){
        $this->redirect('/admin/index/index');
    }
}