<?php
/**
 * create by XinyuLi
 * @since 06/06/2020 16:18
 */

namespace app\common\model\mysql;
use think\Model;

class ModelBase extends Model
{
    public function updateById($id,$data){
        $data['update_time'] = time();
        return $this->where(['id'=>$id])->save($data);
    }

    public function getNormalInIds($ids){
        return $this->whereIn('id',$ids)
            ->where('status','=',config('status.mysql.table_normal'))
            ->select();
    }

    //根据条件查询订单信息
    public function getByCondition($condition = [], $order = ['id'=>'desc']){
        if (!$condition || !is_array($condition)){
            return false;
        }
        $result = $this->where($condition)
            ->order($order)
            ->select();  //二维数组
        return $result;
    }
}