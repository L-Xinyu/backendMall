<?php
/**
 * create by XinyuLi
 * @since 07/06/2020 12:06
 */

namespace app\common\model\mysql;
use think\Model;

class AdminUser extends Model
{
    /**
     * getAdminUserByUsername 根据用户名获取后端表数据
     * @param $username
     * @return array|bool|Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getAdminUserByUsername($username){
        if (empty($username)){
            return false;
        }
        $where = [
            'username' => trim($username),
        ];
        $result = $this->where($where)->find();
        return $result;
    }

    /**
     * update about ID
     * @param $id
     * @param $data
     * @return bool
     */
    public function updateByID($id,$data){
        $id = intval($id);
        if (empty($id)||empty($data)||!is_array($data)){
            return false;
        }
        $where = [
            'id' => $id
        ];

        return $this->where($where)->save($data);
    }
}