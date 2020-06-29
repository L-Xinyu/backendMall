<?php
/**
 * create by XinyuLi
 * @since 10/06/2020 14:56
 */

namespace app\common\business;
use app\common\lib\Time;
use app\common\model\mysql\User as UserModel;
use think\Exception;
use app\common\lib\Token;
class User
{
    public $userObj = null;
    public function __construct(){
        $this->userObj = new UserModel();
    }

    //User register
    public function regUser($data){
        if (!is_array($data)){
            throw new Exception('The data not array！');
        }
        $regUser = $this->userObj->getUserByPhoneNumber($data['phone_number']);
        if (!empty($regUser)) {
            throw new Exception('User already exists, please login!');
        }

        if (!$regUser){
            $userData = [
                'username' => $data['username'],
                'phone_number' => $data['phone_number'],
                'password' => $data['password'],
                'status' => config('status.mysql.table_normal'),
            ];
            try {
                $reg = $this->userObj->save($userData);
            }catch (\Exception $e){
                throw new Exception('Database server Error!');
            }
        }
        return $reg;
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
        }else{
            $userId = $user->id;
            $username = $user->username;
        }
        $token = Token::getLoginToken($data['phone_number']);
        $redisData = [
            'id' => $userId,
            'username' => $username,
        ];
        $res = cache(config('redis.token_pre').$token,$redisData,Time::userLoginExpiresTime([$data['type']]));
        //返回给前端数据记录session
        return $res ? ['token'=>$token,'username'=>$username] : false;
    }

    //return normal user data
    public function getNormalUserById($id){
        $user = $this->userObj->getUserById($id);
        if (!$user || $user->status != config('status.mysql.table_normal')){
            return [];
        }
        return $user->toArray();
    }

    public function getNormalUserByUsername($username){
        $user = $this->userObj->getUserByUsername($username);
        if (!$user || $user->status != config('status.mysql.table_normal')){
            return [];
        }
        return $user->toArray();
    }

    public function update($id,$data){
        $user = $this->getNormalUserById($id);
        if (!$user){
            throw new Exception('no exist the user!');
        }
        //username exist?
        $userResult = $this->getNormalUserByUsername($data['username']);
        if ($userResult && $userResult['id'] != $id){
            throw new Exception('This user already exists, please reset it...');
        }
        return $this->userObj->updateById($id,$data);
    }

    public function getAllUsers(){
        $user = $this->userObj->getAllUsers();
        if (!$user){
            return [];
        }
        $allUser = $user->toArray();
        return $allUser;
    }
}