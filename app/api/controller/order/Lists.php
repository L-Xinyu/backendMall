<?php
/**
 * create by XinyuLi
 * @since 25/06/2020 0:13
 */

namespace app\api\controller\order;
use app\api\controller\AuthBase;
use app\common\business\Order as OrderBusiness;
use app\common\lib\Show;

class Lists extends AuthBase
{
    //get all order information
    public function getAllOrders(){
        $condition = [
            'user_id' => $this->userId,
        ];
        $result = (new OrderBusiness())->getAllOrders($condition);
        if (!$result){
            return Show::error('Error getting all order information!');
        }
        return Show::success($result);
    }
}