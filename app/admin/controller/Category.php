<?php
/**
 * create by XinyuLi
 * @since 08/06/2020 18:42
 */

namespace app\admin\controller;
use think\facade\View;
use app\common\business\Category as CategoryBusiness;

class Category extends AdminBase
{
    public function index(){
        return View::fetch();
    }

    public function add(){
        try {
            $categories = (new CategoryBusiness())->getNormalCategories();
        }catch (\Exception $e){
            $categories = [];
        }
        return View::fetch("",[
            'categories' => json_encode($categories),
        ]);
    }

    public function save(){
        $pid = input('param.pid',0,'intval');
        $name = input('param.name',0,'trim');

        $data = [
            'pid' => $pid,
            'name' => $name,
        ];
        $validate = new \app\admin\validate\Category();
        if (!$validate->check($data)){
            return show(config('status.error'),$validate->getError());
        }

        try {
            $result = (new CategoryBusiness())->add($data);
        } catch (\Exception $e) {
            return show(config('status.error'), $e->getMessage());
        }
            return show(config("status.success"), "OK");
    }
}