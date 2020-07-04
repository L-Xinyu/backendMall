<?php
/**
 * create by XinyuLi
 * @since 08/06/2020 20:03
 */

namespace app\common\model\mysql;
use think\Model;

class Category extends ModelBase
{
    //Get category information
    public function getNormalCategories($field = "*"){
        $where = [
            'status' => config('status.mysql.table_normal'),
        ];
        $order = [
            'listorder' => 'desc',
            'id' => 'desc'
        ];
        $result = $this->where($where)
            ->field($field)
            ->order($order)
            ->select();
        return $result;
    }

    //get list
    public function getLists($where,$num = 10){
        $order = [
            'listorder' => 'desc',
            'id' => 'desc'
        ];
        //get data where status!=99
        $result = $this->where('status','<>',config('status.mysql.table_delete'))
            ->where($where)  //pid
            ->order($order)
            ->paginate($num);
        return $result;
    }

    //获取每个id下子分类数量get Subcategories counts
    public function getChildCountInPids($condition){
        $res = $this->where('pid','in',$condition['pid'])
            ->where('status','<>',config('status.mysql.table_delete'))
            ->field(['pid','count(*) as count'])
            ->group('pid')
            ->select();
        return $res;
    }

    //get first category
    public function getNormalByPid($pid = 0,$field){
        $where = [
            'pid' => $pid,
            'status' => config('status.mysql.table_normal'),
        ];
        $order = [
            'listorder' => 'desc',
            'id' => 'desc',
        ];
        $res = $this->where($where)
            ->field($field)
            ->order($order)
            ->select();
        return $res;
    }

    /**
     * 几级分类获取器
     * @param $value
     * @return int
     */
    public function getSeriesAttr($value)
    {
        return count(explode(',',$value));
    }


    /**
     * 根据一级分类ID查找该分类下的所有分类
     * @param $id
     * @param bool $field
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getCategoryFindInSet($id,$field = true)
    {
        $order = [
            'listorder'=>'desc',
            'id'=>'desc'
        ];
        $res = $this->where('status',config('status.mysql.table_normal'))
            ->field($field)
            ->whereFindInSet('path',$id)
            ->order($order)
            ->select();

        return $res;
    }

    /**
     * Get the recommended items on the homepage
     * @param $categoryIds
     * @param bool $field
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getNormalCategoryInPidOrId($categoryIds, $field = true){
        $order = [
            'listorder'=>'desc',
            'id'=>'desc'
        ];
        $where[] = ['id|pid', 'in', $categoryIds];
        $where[] = ['status', '=', config('status.mysql.table_normal')];
        $res = $this->where($where)
            ->field($field)
            ->order($order)
            ->select();

        return $res->toArray();
    }
}