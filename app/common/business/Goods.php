<?php
/**
 * create by XinyuLi
 * @since 12/06/2020 16:39
 */

namespace app\common\business;
use app\common\model\mysql\Goods as GoodsModel;

class Goods extends BusinessBase
{
    public $model = NULL;

    public function __construct()
    {
        $this->model = new GoodsModel();
    }

    public function insertData($data){
        $goodsId = $this->add($data);

        if(!$goodsId){
            return $goodsId;
        }
    }
}