<?php
/**
 * 需要登录场景时继承
 * create by XinyuLi
 * @since 10/06/2020 19:45
 */

namespace app\api\controller;

class AuthBase extends ApiBase
{
    public $userId = 0;
    public $username = '';
    public $accessToken = '';

    public function initialize()
    {
        parent::initialize();
        $this->accessToken = $this->request->header('access-token');
        if (!$this->accessToken || !$this->isLogin()){
            return $this->show(config('status.not_login'),'You are not logged in...');
        }

    }

    /**
     * 判断用户是否登录
     * @return bool
     */
    public function isLogin(){
        $userInfo = cache(config('redis.token_pre').$this->accessToken);
        if (!$userInfo){
            return false;
        }
        if (!empty($userInfo['id']) && !empty($userInfo['username'])){
            $this->userId = $userInfo['id'];
            $this->username = $userInfo['username'];
            return true;
        }
        return false;
    }
}