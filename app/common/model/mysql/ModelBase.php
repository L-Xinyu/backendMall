<?php
/**
 * create by XinyuLi
 * @since 06/06/2020 16:18
 */

namespace app\common\model\mysql;
use think\Model;

class ModelBase extends Model
{
    protected $autoWriteTimestamp = true;

    public function updateById($id,$data){
        $data['update_time'] = time();
        return $this->where(['id'=>$id])->save($data);
    }
}