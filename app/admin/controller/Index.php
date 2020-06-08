<?php
/**
 * create by XinyuLi
 * @since 07/06/2020 0:20
 */

namespace app\admin\controller;
use think\facade\View;

class Index extends AdminBase
{
    public function index(){
        return View::fetch();
    }

    public function welcome(){
        return View::fetch();
    }
}