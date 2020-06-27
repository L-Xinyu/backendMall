<?php
/**
 * create by XinyuLi
 * @since 10/06/2020 19:55
 */

namespace app\api\controller;
use app\common\business\User as UserBusiness;
use app\common\lib\Show;

class User extends AuthBase
{
    public function index(){
        $user = (new UserBusiness())->getNormalUserById($this->userId);
        $resultUser = [
            'id' => $this->userId,
            'username' => $user['username'],
            'sex' => $user['sex'],
        ];
        return show(config('status.success'),'OK',$resultUser);
    }

    //user register
    public function register(){
        if (!$this->request->isPost()){
            return Show::error("Incorrect request method...");
        }

        $phoneNumber = input('phone_number','','trim');
        $username = input('param.username','','trim');
        $password = input('param.password','','trim');

        $data = [
            'phone_number' => $phoneNumber,
            'username' => $username,
            'password' => $password
        ];
        $data['salt'] = mt_rand(100,10000);
        $data['password'] = md5($data['password'].$data['salt']);

        try {
            $reg = (new UserBusiness())->regUser($data);
        }catch (\Exception $e){
            return show(config('status.error'),$e->getMessage());
        }

        if (!$reg){
            return show(config('status.error'),'Failed to register...');
        }
        return Show::success('Success to register!');
    }

    //update user information
    public function update(){
        $username = input('param.username','','trim');
        $sex = input('param.sex',0,'intval');

        $data = [
            'username' => $username,
            'sex' => $sex,
        ];
        $validate = (new \app\api\validate\User)->scene('update_user');
        if (!$validate->check($data)){
            return show(config('status.error'),$validate->getError());
        }
        $userBusinessObj = new UserBusiness();
        $user = $userBusinessObj->update($this->userId,$data);
        if (!$user){
            return show(config('status.error'),'Failed to update...');
        }
        return show(1,'OK');
    }
}