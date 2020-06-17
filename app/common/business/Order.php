<?php
/**
 * create by XinyuLi
 * @since 17/06/2020 14:59
 */

namespace app\common\business;
use app\common\model\mysql\Order as OrderModel;
use Godruoyi\Snowflake\Snowflake;
use app\common\business\Cart;
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
        $cartObj = new Cart();
        $result = $cartObj->lists($data['user_id'],$data['ids']);
        dump($result);exit;
    }
}