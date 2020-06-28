<?php
/**
 * create by XinyuLi
 * @since 10/06/2020 19:55
 */

namespace app\api\controller;
use app\common\business\User as UserBusiness;

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

    //user logout
    public function logout(){
        //delete redis cache
        $res = cache(config('redis.token_pre').$this->accessToken,NULL);

        if ($res){
            return show(config('status.success'),'Success Logout!');
        }
        return show('status.error','Failed Logout!');
    }
}