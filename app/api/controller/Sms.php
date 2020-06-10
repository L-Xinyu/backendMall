<?php
/**
 * create by XinyuLi
 * @since 09/06/2020 21:58
 */

declare(strict_types=1);
namespace app\api\controller;
use app\api\validate\User;
use app\BaseController;
use think\exception\ValidateException;
use app\common\business\Sms as SmsBusiness;

class Sms extends BaseController
{
    public function code() :object{
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