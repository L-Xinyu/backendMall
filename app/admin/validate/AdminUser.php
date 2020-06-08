<?php
/**
 * create by XinyuLi
 * @since 07/06/2020 21:22
 */

namespace app\admin\validate;
use think\Validate;

class AdminUser extends Validate
{
    protected $rule = [
        'username' => 'require',
        'password' => 'require',
        'captcha' => 'require|checkCaptcha',
    ];

    protected $message = [
        'username' => 'username cannot be empty!',
        'password' => 'password cannot be empty!',
        'captcha' => 'captcha cannot be empty!',
    ];

    protected function checkCaptcha($value,$rule,$data = []){
        if (!captcha_check($value)){
            return 'The captcha is wrong';
        }
        return true;
    }
}