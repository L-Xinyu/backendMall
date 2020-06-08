<?php
/**
 * create by XinyuLi
 * @since 08/06/2020 18:42
 */

namespace app\admin\controller;


use think\facade\View;

class Category extends AdminBase
{
    public function index(){
        return View::fetch();
    }

    public function add(){
        return View::fetch();
    }
}