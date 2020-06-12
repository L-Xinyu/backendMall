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
}