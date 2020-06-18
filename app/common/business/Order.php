<?php
/**
 * create by XinyuLi
 * @since 17/06/2020 14:59
 */

namespace app\common\business;
use app\common\model\mysql\Order as OrderModel;
use app\common\model\mysql\OrderGoods as OrderGoodsModel;
use Godruoyi\Snowflake\Snowflake;
use app\common\business\Cart as CartBusiness;
class Order extends BusinessBase
{
    public $model = NULL;

    public function __construct(){
        $this->model = new OrderModel();
    }

    public function save($data){
        //get a order number
        $workerId = rand(1,100);
        $snowflake = new Snowflake($workerId);
        $orderId = $snowflake->id();

        //get shoppingCart data =>redis
        $cartObj = new CartBusiness();
        $result = $cartObj->lists($data['user_id'],$data['ids']);
        if (!$result){
            return false;
        }
        $newResult = array_map(function ($v) use($orderId){
            $v['sku_id'] = $v['id'];
            unset($v['id']);
            $v['order_id'] = $orderId;
            return $v;
        },$result);
        //total price
        $price = array_sum(array_column($result,'total_price'));
        $orderData = [
            'user_id' => $data['user_id'],
            'order_id' => $orderId,
            'total_price' => $price,
            'address_id' => $data['address_id'],
        ];
        //事务处理
        $this->model->startTrans();
        try {
            //add order
            $id = $this->add($orderData);
            if (!$id){
                return 0;
            }
            //add order_goods
            $orderGoodsResult = (new OrderGoodsModel())->saveAll($newResult);
            //update goods_sku
            $skuRes = (new GoodsSku())->updateStock($result);
            //TODO Goods 更新
            //delete goods in shoppingCart
            $cartObj->deleteRedis($data['user_id'], $data['ids']);

            $this->model->commit();
            return ['id' => $orderId];
        }catch (\Exception $e){
            $this->model->rollback();
            return false;
        }
    }
}