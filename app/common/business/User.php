<?php
/**
 * create by XinyuLi
 * @since 10/06/2020 14:56
 */

namespace app\common\business;
use app\common\model\mysql\User as UserModel;
use think\Exception;

class User
{
    public $userObj = null;
    public function __construct(){
        $this->userObj = new UserModel();
    }

    public function login($data){
        $redisCode = cache(config('redis.code_pre').$data['phone_number']);
        if (empty($redisCode) || $redisCode != $data['code']){
            throw new Exception('No exist this MessageCode!',-2001);
        }
        //判断表是否有记录 phone_number 生成token
        $user = $this->userObj->getUserByPhoneNumber($data['phone_number']);
        if (!$user){
            //if no exist,create new user
            $username = 'LXYMall-'.$data['phone_number'];
            $userData = [
                'username' => $username,
                'phone_number' => $data['phone_number'],
                'type' => $data['type'],
                'status' => config('status.mysql.table_normal'),
            ];
            try {
                $this->userObj->save($userData);
                $userId = $this->userObj->id;
            }catch (\Exception $e){
                throw new Exception('Database server Error!');
            }
        }
        return true;
    }
}