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
use app\common\business\SpecsValue as SpecsValueBusiness;
use think\facade\Cache;

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
        $likeKeys = [];
        if(!empty($data)) {
            $likeKeys = array_keys($data);
        }
        try {
            $list = $this->model->getLists($likeKeys, $data, $num);
            $result = $list->toArray();
        }catch (\Exception $e) {
            $result = Arr::getPaginateDefaultData($num);
        }
        return $result;
    }

    /**
     * 首页轮播图
     * @return array
     */
    public function getRotationChart(){
        $data = [
            'is_index_recommend' => 1,
        ];
        $field = 'sku_id as id, title, big_image as image';
        try {
            $result = $this->model->getNormalGoodsByCondition($data,$field,5);
        }catch (\Exception $e){
            return [];
        }
        return $result->toArray();
    }

    /**
     * 首页推荐Home Goods
     * @param $categoryIds
     * @return array
     */
    public function cagegoryGoodsRecommend($categoryIds){
        if (!$categoryIds){
            return [];
        }
        //栏目的获取？？？？
        foreach ($categoryIds as $k => $categoryId){
            $result[$k]['categorys'] = [];
        }

        foreach ($categoryIds as $key => $categoryId){
            $result[$key]['goods'] = $this->getNormalGoodsFindInSetCategoryId($categoryId);
        }
        return $result;
    }

    public function getNormalGoodsFindInsetCategoryId($categoryId){
        $field = 'sku_id as id, title, price, recommend_image as image';
        try {
            $result = $this->model->getNormalGoodsFindInSetCategoryId($categoryId, $field);
        }catch (\Exception $e){
            return [];
        }
        return $result->toArray();
    }

    /**
     * Goods pagination display
     * @param $data
     * @param int $num
     * @param $order
     * @return array
     */
    public function getNormalLists($data, $num = 5, $order) {
        try {
            $field = "sku_id as id, title, recommend_image as image,price";
            $list = $this->model->getNormalLists($data, $num, $field, $order);
            $res = $list->toArray();
            //转换分页数据格式
            $result = [
                "total_page_num" => isset($res['last_page']) ? $res['last_page'] : 0,
                "count" => isset($res['total']) ? $res['total'] : 0,
                "page" => isset($res['current_page']) ? $res['current_page'] : 0,
                "page_size" => $num,
                "list" => isset($res['data']) ? $res['data'] : []
            ];
        }catch (\Exception $e) {
            //echo $e->getMessage();exit;
            $result = [];
        }
        return $result;
    }

    //goods details
    public function getGoodsDetailBySkuId($skuId){
        $skuBisObj = new GoodsSkuBusiness();
        $goodsSku =  $skuBisObj->getNormalSkuAndGoods($skuId);
        if (!$goodsSku){
            return [];
        }
        if (empty($goodsSku['goods'])){
            return [];
        }
        $goods = $goodsSku['goods'];
        //detail SKU
        if ($goods['goods_specs_type'] == 1){  //统一规格
            $sku = [];
        }else{  //多规格优化
            $skus = $skuBisObj->getSkusByGoodsId($goods['id']);
            if (!$skus){
                return [];
            }

            $flagValue= '';
            foreach ($skus as $s) {
                if ($s['id'] == $skuId){
                    $flagValue = $s['specs_value_ids'];//sku下的属性值
                }
            }
            $gids = array_column($skus,'id','specs_value_ids');
            $sku = (new SpecsValueBusiness())->detailGoodsSkus($gids, $flagValue);
        }

        $result = [
            'title' => $goods['title'],
            'price' => $goodsSku['price'],
            'cost_price' => $goodsSku['cost_price'],
            'sales_count' => 0,
            'stock' => $goodsSku['stock'],
            'gids' => $gids,
            'image' => $goods['carousel_image'],
            'sku' => $sku,
            'detail' => [
                'd1' => [
                    'Goods_Code' => $goodsSku['id'],
                    'Added_time' => $goods['create_time'],
                ],
                'd2' => preg_replace('/(<img.+?src=")(.*?)/',
                    '$1'.request()->domain().'$2', $goods['description']),
            ],
        ];

        //record goods redis
        Cache::inc(config('redis.goods_search_pre').$goods['id']);

        return $result;
    }

    /**
     * Get new Goods Recommend
     * @param $count
     * @return array|\think\Collection
     */
    public function newGoodsRecommend($count){
        try {
            $field = "id, title, price, recommend_image as image";
            $goods = $this->model->getNewGoods($count,$field);
        }catch (\Exception $e){
            $goods = [];
        }
        return $goods;
    }
}