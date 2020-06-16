<?php
/**
 * create by XinyuLi
 * @since 15/06/2020 14:51
 */

namespace app\api\controller;
use app\common\lib\Show;
use app\common\business\Cart as CartBusiness;

class Cart extends AuthBase
{
    //add to shoppingCart
    public function add(){
        if (!$this->request->isPost()){
            return Show::error();
        }
        $id = input('param.id',0,'intval');
        $num = input('param.num',0,'intval');
        if (!$id || !$num){
            return Show::error([],'参数不合法！');
        }
        $res = (new CartBusiness())->insertRedis($this->userId,$id,$num);
        if ($res === FALSE){
            return Show::error();
        }
        return Show::success();
    }

    //Get shoppingCart lists
    public function lists(){
        $res = (new CartBusiness())->lists($this->userId);
        if ($res === FALSE){
            return Show::error();
        }
        return Show::success($res);
    }

    //delete goods at shoppingCart
    public function delete(){
        if (!$this->request->isPost()){
            return Show::error();
        }

        $id = input('param.id',0,'intval');
        if (!$id){
            return Show::error([],'Parameter is invalid...');
        }
        $res = (new CartBusiness())->deleteRedis($this->userId,$id);
        if ($res === FALSE){
            return Show::error();
        }
        return Show::success($res);
    }

    //update cart goods
    public function update() {
        if(!$this->request->isPost()) {
            return Show::error();
        }

        $id = input("param.id", 0, "intval");
        $num = input("param.num", 0, "intval");
        if(!$id || !$num) {
            return Show::error('Parameter is invalid...');
        }

        try {
            $res = (new CartBusiness())->updateRedis($this->userId, $id, $num);
        }catch (\Exception $e) {
            return Show::error($e->getMessage());
        }
        if($res === FALSE) {
            return Show::error();
        }
        return Show::success($res);
    }
}