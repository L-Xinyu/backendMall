<?php
/**
 * create by XinyuLi
 * @since 07/06/2020 12:06
 */

namespace app\common\model\mysql;
use think\Model;

class User extends Model
{
    //AutoWriteTime
    protected $autoWriteTimestamp = true;
    public function getUserByPhoneNumber($phoneNumber){
        if (empty($phoneNumber)){
            return false;
        }
        $where = [
            'phone_number' => $phoneNumber,
        ];
        $result = $this->where($where)->find();
        return $result;
    }
}