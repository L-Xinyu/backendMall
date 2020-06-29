<?php
/**
 * create by XinyuLi
 * @since 28/06/2020 21:12
 */

namespace app\admin\controller;
use app\common\business\User as UserBusiness;

class User extends AdminBase
{
    public function index(){
        try {
            $user = (new UserBusiness())->getAllUsers();
        }catch (\Exception $e){
            return show(config('status.success'),'Internal exception...');
        }
        return view("", [
            "user" => $user,
        ]);
    }
}