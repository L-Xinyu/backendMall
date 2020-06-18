<?php
/**
 * create by XinyuLi
 * @since 17/06/2020 15:03
 */

namespace app\common\business;
use app\common\model\mysql\OrderGoods as OrderGoodsModel;

class OrderGoods extends BusinessBase
{
    public $model = NULL;

    public function __construct(){
        $this->model = new OrderGoodsModel();
    }

    //根据订单id获取order_goods表中的数据
    public function getByOrderId($orderId){
        $condition = [
            'order_id' => $orderId,
        ];
        try {
            $orders = $this->model->getByCondition($condition);
        }catch (\Exception $e){
            $orders = [];
        }
        if (!$orders){
            return [];
        }
        $orders = $orders->toArray();
        return $orders;
    }
}