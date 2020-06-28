<?php
/**
 * create by XinyuLi
 * @since 07/06/2020 15:28
 */

namespace app\admin\controller;
use app\BaseController;
use think\exception\HttpResponseException;

class AdminBase extends BaseController
{
    public $adminUser = null;
    public function initialize()
    {
        parent::initialize();
        if(empty($this->isLogin())){
            return $this->redirect(url('login/index'),302);
        }
    }

    /**
     * is Auth login??
     * @return bool
     */
    public function isLogin(){
        $this->adminUser = session(config('admin.session_admin'));
        if(empty($this->adminUser)){
            return false;
        }
        return true;
    }

    public function redirect(...$args){
        throw new HttpResponseException(redirect(...$args));
    }

}