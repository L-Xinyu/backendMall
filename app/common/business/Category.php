<?php
/**
 * create by XinyuLi
 * @since 08/06/2020 20:00
 */

namespace app\common\business;
use app\common\model\mysql\Category as CategoryModel;

class Category
{
    public $categoryObj = null;
    public function __construct(){
        $this->categoryObj = new CategoryModel();
    }

    public function add($data){
        $data['status'] = config('status.mysql.table_normal');
        $name = $data['name'];
        //新增前去db根据$name查询记录是否存在
        try {
            $this->categoryObj->save($data);
        }catch (\Exception $e){
            throw new \think\Exception('Server Error!');
        }

        //返回新增id
        return $this->categoryObj->getLastInsID();
    }

    public function getNormalCategories(){
        $field = 'id, name, pid';
        $categories = $this->categoryObj->getNormalCategories($field);
        if (!$categories){
            return $categories;
        }
        $categories = $categories->toArray();
        return $categories;
    }
    //Paginated list
    public function getList($data,$num){
        $list = $this->categoryObj->getLists($data,$num);
        if (!$list){
            return [];
        }
        $list = $list->toArray();
        return $list;
    }
}