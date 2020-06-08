<?php
/**
 * create by XinyuLi
 * @since 06/06/2020 22:58
 */
namespace app\admin\controller;

use think\facade\View;
use app\common\model\mysql\AdminUser;

class Login extends AdminBase{

    public function initialize()
    {
        if ($this->isLogin()){
            return $this->redirect(url('index/index'));
        }
    }

    public function index(){
        return View::fetch();
    }

    public function md5(){
        halt(session(config('admin.session_admin')));
        echo md5("admin");
    }

    public function check(){
        if(!$this->request->isPost()){
            return show(config('status.error'),'Incorrect request method!');
        }

        //参数校验
        $username = $this->request->param('username','','trim');
        $password = $this->request->param('password','','trim');
        $captcha = $this->request->param('captcha','','trim');

        $validate = new \app\admin\validate\AdminUser();
        $data = [
            'username' => $username,
            'password' => $password,
            'captcha' => $captcha,
        ];
        if (!$validate->check($data)){
            return show(config('status.error'),$validate->getError());
        }

        try {
            $adminUserObj = new \app\admin\business\AdminUser();
            $result = $adminUserObj->login($data);
        }catch (\Exception $e){
            return show(config('status.error'),$e->getMessage());
        }
        if ($result){
            return show(config('status.success'),'success login~~~');
        }
        return show(config('status.error'),$validate->getError());
    }

}