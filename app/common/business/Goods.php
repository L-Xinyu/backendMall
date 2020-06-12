<?php
/**
 * create by XinyuLi
 * @since 12/06/2020 16:39
 */

namespace app\common\business;
use app\common\lib\Arr;
use app\common\model\mysql\Goods as GoodsModel;
use app\common\business\GoodsSku as GoodsSkuBusiness;
use think\Exception;

class Goods extends BusinessBase
{
    public $model = NULL;
    public function __construct()
    {
        $this->model = new GoodsModel();
    }

    public function insertData($data){
        // 开启一个事务
        $this->model->startTrans();
        try {
        $goodsId = $this->add($data);
        if(!$goodsId){
            return $goodsId;
        }
        if ($data['goods_specs_type'] == 1){
            $goodsSkuData = [
                'goods_id' => $goodsId,
            ];
            return true;
        }elseif($data['goods_specs_type'] == 2){ //多规格
            $goodsSkuBisobj = new GoodsSkuBusiness();
            $data['goods_id'] = $goodsId;
            $res = $goodsSkuBisobj->saveAll($data);
            if (!empty($res)){
                //sum stock
                $stock = array_sum(array_column($res,'stock'));
                $goodsUpdateData = [
                    'price' => $res[0]['price'],
                    'cost_price' => $res[0]['cost_price'],
                    'stock' => $stock,
                    'sku_id' => $res[0]['id'],
                ];
                //update data in DB
                $goodsRes = $this->model->updateById($goodsId,$goodsUpdateData);
                if (!$goodsRes) {
                    throw  new Exception("insertData:goods主表更新失败");
                }
            } else {
                throw new Exception("sku表新增失败");
            }
        }
            // 事务提交
            $this->model->commit();
            return true;
        }catch (Exception $e) {
            // 事务回滚
            $this->model->rollback();
            return false;
        }
    }

    /**
     * 获取分页列表的数据
     * @param $data
     * @param int $num
     * @return array
     */
    public function getLists($data, $num = 5) {
        try {
            $list = $this->model->getLists($data, $num);
            $result = $list->toArray();
        }catch (\Exception $e) {
            $result = Arr::getPaginateDefaultData($num);
        }
        return $result;
    }

}