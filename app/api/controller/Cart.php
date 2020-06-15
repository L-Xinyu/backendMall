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
}