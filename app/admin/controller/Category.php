<?php
/**
 * create by XinyuLi
 * @since 08/06/2020 18:42
 */

namespace app\admin\controller;
use app\common\lib\Arr;
use app\common\lib\Status as StatusLib;
use think\facade\View;
use app\common\business\Category as CategoryBusiness;

class Category extends AdminBase
{
    public function index(){
        $pid = input('param.pid',0,'intval');
        $data = [
            'pid' => $pid,
        ];
        try {
            $categories = (new CategoryBusiness())->getList($data,5);
        }catch (\Exception $e){
            $categories = Arr::getPaginateDefaultData(5);
        }
        return View::fetch("",[
            'categories' => $categories,
            'pid' => $pid,
        ]);
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
        if ($result){
            return show(config("status.success"), "OK");
        }
        return show(config("status.error"), "Failed to add new category!");
    }

    //Sort categories
    public function listorder(){
        $id = input('param.id',0,'intval');
        $listorder = input('param.listorder',0,'intval');

        if (!$id){
            return show(config('status.error'),'Parameter error');
        }
        try {
            $res = (new CategoryBusiness())->listorder($id,$listorder);
        }catch (\Exception $e){
            return show(config('status.error'), $e->getMessage());
        }
        if ($res){
            return show(config("status.success"), "Success sort");
        }else{
            return show(config('status.error'),'Failed sort');
        }
    }

    //update status
    public function status(){
        $status = input('param.status',0,'intval');
        $id = input('param.id',0,'intval');

        if (!$id||!in_array($status,StatusLib::getTableStatus())){
            return show(config('status.error'),'Parameter error');
        }
        try {
            $res = (new CategoryBusiness())->status($id,$status);
        }catch (\Exception $e){
            return show(config('status.error'), $e->getMessage());
        }
        if ($res){
            return show(config("status.success"), "Success update status~");
        }else{
            return show(config('status.error'),'Failed update status!');
        }

    }

    //获取一级分类数据
    public function dialog(){
        $categories = (new CategoryBusiness())->getNormalByPid();
        return view("",[
            'categories' => json_encode($categories),
        ]);
    }
    //二级分类数据
    public function getByPid(){
        $pid = input('param.pid',0,'intval');
        $categories = (new CategoryBusiness())->getNormalByPid($pid);
        return show(config('status.success'),'OK~~~',$categories);
    }


}