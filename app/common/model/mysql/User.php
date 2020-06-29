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
    //Get user data by userid
    public function getUserById($id){
        $id = intval($id);
        if (!$id){
            return false;
        }
        return $this->find($id);
    }

    //Get userInfo by username
    public function getUserByUsername($username){
        if (empty($username)){
            return false;
        }
        $where = [
            'username' => $username,
        ];
        $result = $this->where($where)->find();
        return $result;
    }

    public function updateById($id, $data) {
        $id = intval($id);
        $data['update_time'] = time();
        if(empty($id) || empty($data) || !is_array($data)) {
            return false;
        }
        $where = ["id" => $id];
        return $this->where($where)->save($data);
    }

    public function getAllUsers(){
        $where = [
            'status' => config('status.mysql.table_normal'),
        ];
        $order = [
            'id' => 'asc'
        ];
        $result = $this->where($where)
            ->order($order)
            ->select();
        return $result;
    }
}