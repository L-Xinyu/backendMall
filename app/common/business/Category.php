<?php
/**
 * create by XinyuLi
 * @since 08/06/2020 20:00
 */

namespace app\common\business;
use app\common\lib\Arr;
use app\common\model\mysql\Category as CategoryModel;
use think\Exception;

class Category
{
    public $model = null;
    public function __construct(){
        $this->model = new CategoryModel();
    }

    public function add($data){
        $data['status'] = config('status.mysql.table_normal');
        $name = $data['name'];
        //新增前去db根据$name查询记录是否存在
        try {
            $this->model->save($data);
        }catch (\Exception $e){
            throw new Exception('Server Error!');
        }
        //返回新增id
        return $this->model->id;
    }

    public function getNormalCategories(){
        $field = 'id, name, pid';
        $categories = $this->model->getNormalCategories($field);
        if (!$categories){
            return $categories;
        }
        $categories = $categories->toArray();
        return $categories;
    }

    public function getALLCategories(){
        $field = 'id as category_id, name, pid';
        $categories = $this->model->getNormalCategories($field);
        if (!$categories){
            return $categories;
        }
        $categories = $categories->toArray();
        return $categories;
    }

    //Paginated list
    public function getList($data,$num){
        $list = $this->model->getLists($data,$num);
        if (!$list){
            return [];
        }
        $result = $list->toArray();
        $result['render'] = $list->render();

        //get Subcategories子分类
        $pids = array_column($result['data'], "id");
        if($pids) {
            $idCountResult = $this->model->getChildCountInPids(['pid' => $pids]);
            $idCountResult = $idCountResult->toArray();

            $idCounts = [];
            foreach($idCountResult as $countResult) {
                $idCounts[$countResult['pid']] = $countResult['count'];
            }
        }
        if($result['data']) {
            foreach($result['data'] as $k => $value) {
                //$a ?? 0 等同于 isset($a) ? $a : 0。
                //在数据中添加一个字段childCount用于计算子栏目
                $result['data'][$k]['childCount'] = $idCounts[$value['id']] ?? 0;
            }
        }
        return $result;
    }

    public function getById($id) {
        $result = $this->model->find($id);
        if(empty($result)) {
            return [];
        }
        $result = $result->toArray();
        return $result;
    }

    /**
     * sort category business
     * @param $id
     * @param $listorder
     * @return bool
     * @throws \think\Exception
     */
    public function listorder($id, $listorder) {
        // Check if the id data exists
        $res = $this->getById($id);
        if(!$res) {
            throw new Exception("The record does not exist");
        }
        $data = [
            "listorder" => $listorder,
        ];

        try {
            $res = $this->model->updateByID($id, $data);
        }catch (\Exception $e) {
            // logger
            return false;
        }
        return $res;
    }

    /**
     * Change category status
     * @param $id
     * @param $status
     * @return bool
     * @throws Exception
     */
    public function status($id,$status){
        $res = $this->getById($id);
        if (!$res){
            throw new Exception('The record does not exist!');
        }
        if ($res['status']==$status){
            throw new Exception('Same status before and after, no need to modify!');
        }

        $data = [
            'status' =>intval($status),
        ];
        try {
            $res = $this->model->updateByID($id,$data);
        }catch (\Exception $e){
            return false;
        }
        return $res;
    }

    /**
     * Get first category content
     * @param int $pid
     * @param string $field
     * @return array
     */
    public function getNormalByPid($pid = 0, $field = "id, name, pid"){
        try {
            $res = $this->model->getNormalByPid($pid,$field);
        }catch (\Exception $e){
            return [];
        }
        $res = $res->toArray();
        return $res;
    }

    /**
     * Get category list information
     * @param $id
     * @return array
     */
    public function getCategorysApi($id)
    {
        //Current classification information
        $info = $this->getById($id);
        if(!$info){
            return [];
        }
        //找到该分类对应的一级分类
        $pathArr = explode(',',$info['path']);
        $firstCategoryId = $pathArr[0];
        //根据一级分类查找所有的分类数据
        try{
            $result = $this->model->getCategoryFindInSet($firstCategoryId,'id,pid,name,path,path as series');
        }catch(\Exception $e){
            trace('api-search-getCategoryFindInSet-'.$e->getMessage(),'error');//LOG
            return [];
        }
        $categorys = $result->toArray();
        $result = (Arr::searchTree($info['name'],$pathArr,$categorys));

        return $result;
    }

    //栏目Get the recommended items on the homepage
    public function getCategoryByCategoryIds($categoryIds){
        $field = 'id as category_id,name,pid';
        try {
            $res = $this->model->getNormalCategoryInPidOrId($categoryIds,$field);
        }catch (\Exception $e){
            $res = [];
        }
        return $res;
    }
}