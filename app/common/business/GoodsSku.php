<?php
/**
 * create by XinyuLi
 * @since 12/06/2020 16:40
 */

namespace app\common\business;
use app\common\model\mysql\GoodsSku as GoodsSkuModel;

class GoodsSku extends BusinessBase
{
    public $model = NULL;

    public function __construct()
    {
        $this->model = new GoodsSkuModel();
    }

    /**
     * 批量新增逻辑
     */
    public function saveAll($data) {
        if(!$data['skus']) {
            return false;
        }

        foreach($data['skus'] as $value) {
            //SKU数据组装
            $insertData[] = [
                "goods_id" => $data['goods_id'],
                "specs_value_ids" => $value['propvalnames']['propvalids'],
                "price" => $value['propvalnames']['skuSellPrice'],
                "cost_price" => $value['propvalnames']['skuMarketPrice'],
                "stock" => $value['propvalnames']['skuStock'],
            ];
        }

        //number_format round
        try {
            $result = $this->model->saveAll($insertData);
            return $result->toArray();
        }catch (\Exception $e) {
            ///echo $e->getMessage();exit;
            // 记录日志
            return false;
        }
        return true;
    }

    public function getNormalSkuAndGoods($id){
        //use goods func at GoodsModel
        try {
            $result = $this->model->with('goods')->find($id);
        }catch (\Exception $e){
            return [];
        }
        //dump($result->toArray());
        if (!$result){
            $result = [];
        }
        $result = $result->toArray();
        if ($result['status'] != config('status.mysql.table_normal')){
            return [];
        }
        return $result;
    }

    //detail 数据组装
    public function getSkusByGoodsId($goodsId = 0){
        try {
            $skus = $this->model->getNormalByGoodsId($goodsId);
        }catch (\Exception $e){
            return [];
        }
        return $skus->toArray();
    }
}