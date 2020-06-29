<?php
/**
 * create by XinyuLi
 * @since 28/06/2020 21:12
 */

namespace app\admin\controller;
use app\common\business\User as UserBusiness;
use app\common\lib\Status as StatusLib;

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

    /**
     * when you want to delete a user
     * update user status->99
     * @return \think\response\Json
     */
    public function userStatus(){
        $status = input('param.status',0,'intval');
        $id = input('param.id',0,'intval');

        if (!$id||!in_array($status,StatusLib::getTableStatus())){
            return show(config('status.error'),'Parameter error');
        }
        try {
            $res = (new UserBusiness())->userStatus($id,$status);
        }catch (\Exception $e){
            return show(config('status.error'), $e->getMessage());
        }
        if ($res){
            return show(config("status.success"), "Success update status~");
        }else{
            return show(config('status.error'),'Failed update status!');
        }
    }
}