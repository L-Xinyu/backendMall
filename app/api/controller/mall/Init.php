<?php
/**
 * create by XinyuLi
 * @since 16/06/2020 14:56
 */

namespace app\api\controller\mall;
use app\api\controller\AuthBase;
use app\common\business\Cart as CartBusiness;
use app\common\lib\Show;

class Init extends AuthBase
{
    public function index(){
        if (!$this->request->isPost()){
            return Show::error();
        }
        $count = (new CartBusiness())->getCount($this->userId);
        $result = [
            'cart_num' => $count,
        ];
        return Show::success($result);
    }
}