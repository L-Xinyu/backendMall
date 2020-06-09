<?php
/**
 * create by XinyuLi
 * @since 08/06/2020 20:03
 */

namespace app\common\model\mysql;
use think\Model;

class Category extends Model
{
    protected $autoWriteTimestamp = true;
    //Get category information
    public function getNormalCategories($field = "*"){
        $where = [
            'status' => config('status.mysql.table_normal'),
        ];
        $result = $this->where($where)->field($field)->select();
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
    //根据ID 更新数据库中的数据
    public function updayeByID($id,$data){
        $data['update_time'] = time();
        return $this->where(['id'=>$id])->save($data);
    }
}