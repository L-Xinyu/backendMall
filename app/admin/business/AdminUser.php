<?php
/**
 * create by XinyuLi
 * @since 07/06/2020 22:14
 */

namespace app\admin\business;
use app\common\model\mysql\AdminUser as AdminUserModel;
use think\Exception;

class AdminUser
{
    public $adminUserObj = null;
    public function __construct(){
        $this->adminUserObj = new AdminUserModel();
    }

    public function login($data)
    {
        $adminUser = $this->adminUserObj->getAdminUserByUsername($data['username']);
        if (!$adminUser){
            throw new Exception('not have the user!');
        }
        //password
        if ($adminUser['password'] != md5($data['password'])) {
            //return show(config('status.error'), 'password wrong!');
            throw new Exception('password wrong!');
        }

        //put info to table mysql
        $updateData = [
            'last_login_time' => time(),
            'last_login_ip' => request()->ip(),
            'update_time' => time(),
        ];
        $res = $this->adminUserObj->updateByID($adminUser['id'], $updateData);
        if (empty($res)) {
            //return show(config('status.error'), 'Failed Login');
            throw new Exception('Failed Login!');
        }

        //session
        session(config('admin.session_admin'), $adminUser);

        return true;
    }

    public function getAdminUserByUsername($username){
        $adminUser = $this->adminUserObj->getAdminUserByUsername($username);

        if (empty($adminUser) || $adminUser->status != config('status.mysql.table_normal')) {
            return [];
        }
        $adminUser = $adminUser->toArray();
        return $adminUser;
    }
}