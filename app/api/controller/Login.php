<?php
/**
 * create by XinyuLi
 * @since 10/06/2020 14:38
 */

declare(strict_types = 1);
namespace app\api\controller;
use app\api\validate\User;
use app\BaseController;

class Login extends BaseController
{
    public function index() :object{
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
        $result = (new \app\common\business\User())->login($data);
        if ($result){
            return show(config('status.success'),'Success to Login!');
        }
        return show(config('status.error'),'Failed to Login!');
    }
}