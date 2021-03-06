<?php
/**
 * create by XinyuLi
 * @since 11/06/2020 13:18
 */

namespace app\api\controller;
use app\common\business\Category as CategoryBusiness;
use app\common\lib\Arr;
use app\common\lib\Show;

class Category extends ApiBase
{
    public function index(){

        try {
            $categoryBusObj = new CategoryBusiness();
            $categories = $categoryBusObj->getALLCategories();
        }catch (\Exception $e){
            return show(config('status.success'),'Internal exception...');
        }
        if (!$categories){
            return show(config('status.success'),'Data is empty!!!');
        }

        $result = Arr::getTree($categories);
        $result = Arr::sliceTreeArr($result);
        return show(config('status.success'),'OK!!!',$result);
    }

    //Get second and third level subcategories
    public function search()
    {
        $id = input('id','','intval');
        if(!$id) {
            return Show::error("Not exist Id");
        }
        $res = (new CategoryBusiness())->getCategorysApi($id);

        return Show::success($res);
    }
}