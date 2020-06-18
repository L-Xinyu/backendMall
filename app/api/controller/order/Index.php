<?php
/**
 * create by XinyuLi
 * @since 17/06/2020 14:47
 */

namespace app\api\controller\order;
use app\api\controller\AuthBase;
use app\common\lib\Show;
use app\common\business\Order as OrderBusiness;

class Index extends AuthBase
{
    public function save(){
        $addressId = input('param.address_id',0,'intval');
        $ids = input('param.ids','','trim');
        if (!$addressId || !$ids){
            return Show::error('Parameter is invalid...');
        }

        $data = [
            'ids' => $ids,
            'address_id' => $addressId,
            'user_id' => $this->userId,
        ];
        try {
            $result = (new OrderBusiness())->save($data);
        }catch (\Exception $e){
            return Show::error($e->getMessage());
        }
        if (!$result){
            return Show::error('Failed to submit order, try again later!');
        }
        return Show::success($result);
    }

    //get order detail
    public function read(){
        $id = input('param.id','0','trim');
        if (empty($id)){
            return Show::error('Parameter is invalid...');
        }
        $data = [
            'user_id' => $this->userId,
            'order_id' => $id,
        ];

        $result = (new OrderBusiness())->detail($data);
        if (!$result){
            return Show::error('获取订单失败');
        }
        return Show::success($result);
    }
}