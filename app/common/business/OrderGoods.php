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
}