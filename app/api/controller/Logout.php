<?php
/**
 * create by XinyuLi
 * @since 10/06/2020 21:31
 */

namespace app\api\controller;

class Logout extends AuthBase
{
    public function index(){
        //delete redis cache
        $res = cache(config('redis.token_pre').$this->accessToken,NULL);

        if ($res){
            return show(config('status.success'),'Success Logout!');
        }
        return show('status.error','Failed Logout!');
    }
}