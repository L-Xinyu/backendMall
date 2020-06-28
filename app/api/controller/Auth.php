<?php
/**
 * create by XinyuLi
 * @since 10/06/2020 14:38
 */

namespace app\api\controller;
use app\api\validate\User;
use app\BaseController;
use app\common\business\Sms as SmsBusiness;
use app\common\business\User as UserBusiness;
use app\common\lib\Show;
use think\exception\ValidateException;

class Auth extends BaseController
{
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


    //User login
    public function login(){
        if (!$this->request->isPost()){
            return show(config('status.error'),'Illegal request...');
        }
        $phoneNumber = $this->request->param('phone_number','','trim');
        $code = input('param.code','0','intval');
        $type = input('param.type','0','intval');

        $data = [
            'phone_number' => $phoneNumber,
            'code' => $code,
            'type' => $type,
        ];
        $validate = new User();
        if (!$validate->scene('login')->check($data)){
            return show(config('status.error'),$validate->getError());
        }
        try {
            $result = (new \app\common\business\User())->login($data);
        }catch (\Exception $e){
            return show($e->getCode(),$e->getMessage());
        }

        if ($result){
            return show(config('status.success'),'Success to Login!',$result);
        }
        return show(config('status.error'),'Failed to Login!');
    }

    //Send smsCode
    public function smsCode(){
        $phoneNumber = input('param.phone_number','','trim');
        $data = [
            'phone_number' => $phoneNumber,
        ];
        try {
            validate(User::class)->scene('send_code')->check($data);
        }catch (ValidateException $e){
            return show(config('status.error'),$e->getError());
        }

        //调用business层数据
        if(SmsBusiness::sendCode($phoneNumber,4)){
            return show(config('status.success'),'OK');
        }
        return show(config('status.error'),'Failed to send code!');
    }
}