<?php
/**
 * create by XinyuLi
 * @since 10/06/2020 14:38
 */

namespace app\api\controller;
use app\api\validate\User;
use app\BaseController;

class Login extends BaseController
{
    public function index(){
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
}