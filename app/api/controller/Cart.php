<?php
/**
 * create by XinyuLi
 * @since 15/06/2020 14:51
 */

namespace app\api\controller;
use app\common\lib\Show;
use think\facade\Cache;
use app\common\business\Cart as CartBusiness;

class Cart extends AuthBase
{
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
}