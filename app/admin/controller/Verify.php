<?php
/**
 * create by XinyuLi
 * @since 07/06/2020 0:03
 */

namespace app\admin\controller;
use think\captcha\facade\Captcha;

class Verify
{
    public function index(){
        return Captcha::create('verify');
    }
}