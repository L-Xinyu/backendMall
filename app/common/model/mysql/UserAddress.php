<?php
/**
 * create by XinyuLi
 * @since 25/06/2020 14:19
 */

namespace app\common\model\mysql;

class UserAddress extends ModelBase
{
    protected $hidden = ['update_time', 'create_time'];

    public function getAddressByUserID($condition = []){
            if (!$condition || !is_array($condition)){
                return false;
            }
            $result = $this->where($condition)
                ->select();  //二维数组
            return $result;
        }
}