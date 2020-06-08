<?php
/**
 * create by XinyuLi
 * @since 07/06/2020 15:51
 */
namespace app\admin\controller;

class Logout extends AdminBase
{
    public function index(){
        //clear session
        session(config('admin.session_admin'),null);
        //jump to login page
        return redirect(url('login/index'));
    }
}